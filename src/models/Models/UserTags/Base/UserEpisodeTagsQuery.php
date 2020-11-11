<?php

namespace Models\UserTags\Base;

use \Exception;
use \PDO;
use Models\Episodes\Episodes;
use Models\Tags\Tags;
use Models\UserTags\UserEpisodeTags as ChildUserEpisodeTags;
use Models\UserTags\UserEpisodeTagsQuery as ChildUserEpisodeTagsQuery;
use Models\UserTags\Map\UserEpisodeTagsTableMap;
use Models\Users\Users;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'user_episode_tags' table.
 *
 *
 *
 * @method     ChildUserEpisodeTagsQuery orderByTagId($order = Criteria::ASC) Order by the tag_id column
 * @method     ChildUserEpisodeTagsQuery orderByEpisodeId($order = Criteria::ASC) Order by the episode_id column
 * @method     ChildUserEpisodeTagsQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildUserEpisodeTagsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildUserEpisodeTagsQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildUserEpisodeTagsQuery groupByTagId() Group by the tag_id column
 * @method     ChildUserEpisodeTagsQuery groupByEpisodeId() Group by the episode_id column
 * @method     ChildUserEpisodeTagsQuery groupByUserId() Group by the user_id column
 * @method     ChildUserEpisodeTagsQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildUserEpisodeTagsQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildUserEpisodeTagsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserEpisodeTagsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserEpisodeTagsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserEpisodeTagsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserEpisodeTagsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserEpisodeTagsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserEpisodeTagsQuery leftJoinEpisodesTags($relationAlias = null) Adds a LEFT JOIN clause to the query using the EpisodesTags relation
 * @method     ChildUserEpisodeTagsQuery rightJoinEpisodesTags($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EpisodesTags relation
 * @method     ChildUserEpisodeTagsQuery innerJoinEpisodesTags($relationAlias = null) Adds a INNER JOIN clause to the query using the EpisodesTags relation
 *
 * @method     ChildUserEpisodeTagsQuery joinWithEpisodesTags($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EpisodesTags relation
 *
 * @method     ChildUserEpisodeTagsQuery leftJoinWithEpisodesTags() Adds a LEFT JOIN clause and with to the query using the EpisodesTags relation
 * @method     ChildUserEpisodeTagsQuery rightJoinWithEpisodesTags() Adds a RIGHT JOIN clause and with to the query using the EpisodesTags relation
 * @method     ChildUserEpisodeTagsQuery innerJoinWithEpisodesTags() Adds a INNER JOIN clause and with to the query using the EpisodesTags relation
 *
 * @method     ChildUserEpisodeTagsQuery leftJoinEpisodeTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the EpisodeTag relation
 * @method     ChildUserEpisodeTagsQuery rightJoinEpisodeTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EpisodeTag relation
 * @method     ChildUserEpisodeTagsQuery innerJoinEpisodeTag($relationAlias = null) Adds a INNER JOIN clause to the query using the EpisodeTag relation
 *
 * @method     ChildUserEpisodeTagsQuery joinWithEpisodeTag($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EpisodeTag relation
 *
 * @method     ChildUserEpisodeTagsQuery leftJoinWithEpisodeTag() Adds a LEFT JOIN clause and with to the query using the EpisodeTag relation
 * @method     ChildUserEpisodeTagsQuery rightJoinWithEpisodeTag() Adds a RIGHT JOIN clause and with to the query using the EpisodeTag relation
 * @method     ChildUserEpisodeTagsQuery innerJoinWithEpisodeTag() Adds a INNER JOIN clause and with to the query using the EpisodeTag relation
 *
 * @method     ChildUserEpisodeTagsQuery leftJoinUsersEpisodeTags($relationAlias = null) Adds a LEFT JOIN clause to the query using the UsersEpisodeTags relation
 * @method     ChildUserEpisodeTagsQuery rightJoinUsersEpisodeTags($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UsersEpisodeTags relation
 * @method     ChildUserEpisodeTagsQuery innerJoinUsersEpisodeTags($relationAlias = null) Adds a INNER JOIN clause to the query using the UsersEpisodeTags relation
 *
 * @method     ChildUserEpisodeTagsQuery joinWithUsersEpisodeTags($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UsersEpisodeTags relation
 *
 * @method     ChildUserEpisodeTagsQuery leftJoinWithUsersEpisodeTags() Adds a LEFT JOIN clause and with to the query using the UsersEpisodeTags relation
 * @method     ChildUserEpisodeTagsQuery rightJoinWithUsersEpisodeTags() Adds a RIGHT JOIN clause and with to the query using the UsersEpisodeTags relation
 * @method     ChildUserEpisodeTagsQuery innerJoinWithUsersEpisodeTags() Adds a INNER JOIN clause and with to the query using the UsersEpisodeTags relation
 *
 * @method     \Models\Episodes\EpisodesQuery|\Models\Tags\TagsQuery|\Models\Users\UsersQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUserEpisodeTags findOne(ConnectionInterface $con = null) Return the first ChildUserEpisodeTags matching the query
 * @method     ChildUserEpisodeTags findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUserEpisodeTags matching the query, or a new ChildUserEpisodeTags object populated from the query conditions when no match is found
 *
 * @method     ChildUserEpisodeTags findOneByTagId(string $tag_id) Return the first ChildUserEpisodeTags filtered by the tag_id column
 * @method     ChildUserEpisodeTags findOneByEpisodeId(string $episode_id) Return the first ChildUserEpisodeTags filtered by the episode_id column
 * @method     ChildUserEpisodeTags findOneByUserId(string $user_id) Return the first ChildUserEpisodeTags filtered by the user_id column
 * @method     ChildUserEpisodeTags findOneByCreatedAt(string $created_at) Return the first ChildUserEpisodeTags filtered by the created_at column
 * @method     ChildUserEpisodeTags findOneByUpdatedAt(string $updated_at) Return the first ChildUserEpisodeTags filtered by the updated_at column *

 * @method     ChildUserEpisodeTags requirePk($key, ConnectionInterface $con = null) Return the ChildUserEpisodeTags by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodeTags requireOne(ConnectionInterface $con = null) Return the first ChildUserEpisodeTags matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUserEpisodeTags requireOneByTagId(string $tag_id) Return the first ChildUserEpisodeTags filtered by the tag_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodeTags requireOneByEpisodeId(string $episode_id) Return the first ChildUserEpisodeTags filtered by the episode_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodeTags requireOneByUserId(string $user_id) Return the first ChildUserEpisodeTags filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodeTags requireOneByCreatedAt(string $created_at) Return the first ChildUserEpisodeTags filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodeTags requireOneByUpdatedAt(string $updated_at) Return the first ChildUserEpisodeTags filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUserEpisodeTags[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUserEpisodeTags objects based on current ModelCriteria
 * @method     ChildUserEpisodeTags[]|ObjectCollection findByTagId(string $tag_id) Return ChildUserEpisodeTags objects filtered by the tag_id column
 * @method     ChildUserEpisodeTags[]|ObjectCollection findByEpisodeId(string $episode_id) Return ChildUserEpisodeTags objects filtered by the episode_id column
 * @method     ChildUserEpisodeTags[]|ObjectCollection findByUserId(string $user_id) Return ChildUserEpisodeTags objects filtered by the user_id column
 * @method     ChildUserEpisodeTags[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildUserEpisodeTags objects filtered by the created_at column
 * @method     ChildUserEpisodeTags[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildUserEpisodeTags objects filtered by the updated_at column
 * @method     ChildUserEpisodeTags[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserEpisodeTagsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\UserTags\Base\UserEpisodeTagsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\UserTags\\UserEpisodeTags', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserEpisodeTagsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserEpisodeTagsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserEpisodeTagsQuery) {
            return $criteria;
        }
        $query = new ChildUserEpisodeTagsQuery();
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
     * @param array[$tag_id, $episode_id, $user_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildUserEpisodeTags|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserEpisodeTagsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserEpisodeTagsTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
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
     * @return ChildUserEpisodeTags A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT tag_id, episode_id, user_id, created_at, updated_at FROM user_episode_tags WHERE tag_id = :p0 AND episode_id = :p1 AND user_id = :p2';
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
            /** @var ChildUserEpisodeTags $obj */
            $obj = new ChildUserEpisodeTags();
            $obj->hydrate($row);
            UserEpisodeTagsTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
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
     * @return ChildUserEpisodeTags|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(UserEpisodeTagsTableMap::COL_TAG_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(UserEpisodeTagsTableMap::COL_EPISODE_ID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(UserEpisodeTagsTableMap::COL_USER_ID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(UserEpisodeTagsTableMap::COL_TAG_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(UserEpisodeTagsTableMap::COL_EPISODE_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(UserEpisodeTagsTableMap::COL_USER_ID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the tag_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTagId('fooValue');   // WHERE tag_id = 'fooValue'
     * $query->filterByTagId('%fooValue%', Criteria::LIKE); // WHERE tag_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $tagId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function filterByTagId($tagId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($tagId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodeTagsTableMap::COL_TAG_ID, $tagId, $comparison);
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
     * @return $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function filterByEpisodeId($episodeId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($episodeId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodeTagsTableMap::COL_EPISODE_ID, $episodeId, $comparison);
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
     * @return $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($userId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodeTagsTableMap::COL_USER_ID, $userId, $comparison);
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
     * @return $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(UserEpisodeTagsTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserEpisodeTagsTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodeTagsTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserEpisodeTagsTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserEpisodeTagsTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodeTagsTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Episodes\Episodes object
     *
     * @param \Models\Episodes\Episodes|ObjectCollection $episodes The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function filterByEpisodesTags($episodes, $comparison = null)
    {
        if ($episodes instanceof \Models\Episodes\Episodes) {
            return $this
                ->addUsingAlias(UserEpisodeTagsTableMap::COL_EPISODE_ID, $episodes->getId(), $comparison);
        } elseif ($episodes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserEpisodeTagsTableMap::COL_EPISODE_ID, $episodes->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEpisodesTags() only accepts arguments of type \Models\Episodes\Episodes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EpisodesTags relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function joinEpisodesTags($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EpisodesTags');

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
            $this->addJoinObject($join, 'EpisodesTags');
        }

        return $this;
    }

    /**
     * Use the EpisodesTags relation Episodes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Episodes\EpisodesQuery A secondary query class using the current class as primary query
     */
    public function useEpisodesTagsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEpisodesTags($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EpisodesTags', '\Models\Episodes\EpisodesQuery');
    }

    /**
     * Filter the query by a related \Models\Tags\Tags object
     *
     * @param \Models\Tags\Tags|ObjectCollection $tags The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function filterByEpisodeTag($tags, $comparison = null)
    {
        if ($tags instanceof \Models\Tags\Tags) {
            return $this
                ->addUsingAlias(UserEpisodeTagsTableMap::COL_TAG_ID, $tags->getId(), $comparison);
        } elseif ($tags instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserEpisodeTagsTableMap::COL_TAG_ID, $tags->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEpisodeTag() only accepts arguments of type \Models\Tags\Tags or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EpisodeTag relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function joinEpisodeTag($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EpisodeTag');

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
            $this->addJoinObject($join, 'EpisodeTag');
        }

        return $this;
    }

    /**
     * Use the EpisodeTag relation Tags object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Tags\TagsQuery A secondary query class using the current class as primary query
     */
    public function useEpisodeTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEpisodeTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EpisodeTag', '\Models\Tags\TagsQuery');
    }

    /**
     * Filter the query by a related \Models\Users\Users object
     *
     * @param \Models\Users\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function filterByUsersEpisodeTags($users, $comparison = null)
    {
        if ($users instanceof \Models\Users\Users) {
            return $this
                ->addUsingAlias(UserEpisodeTagsTableMap::COL_USER_ID, $users->getId(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserEpisodeTagsTableMap::COL_USER_ID, $users->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUsersEpisodeTags() only accepts arguments of type \Models\Users\Users or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UsersEpisodeTags relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function joinUsersEpisodeTags($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UsersEpisodeTags');

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
            $this->addJoinObject($join, 'UsersEpisodeTags');
        }

        return $this;
    }

    /**
     * Use the UsersEpisodeTags relation Users object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Users\UsersQuery A secondary query class using the current class as primary query
     */
    public function useUsersEpisodeTagsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUsersEpisodeTags($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UsersEpisodeTags', '\Models\Users\UsersQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUserEpisodeTags $userEpisodeTags Object to remove from the list of results
     *
     * @return $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function prune($userEpisodeTags = null)
    {
        if ($userEpisodeTags) {
            $this->addCond('pruneCond0', $this->getAliasedColName(UserEpisodeTagsTableMap::COL_TAG_ID), $userEpisodeTags->getTagId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(UserEpisodeTagsTableMap::COL_EPISODE_ID), $userEpisodeTags->getEpisodeId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(UserEpisodeTagsTableMap::COL_USER_ID), $userEpisodeTags->getUserId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user_episode_tags table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserEpisodeTagsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserEpisodeTagsTableMap::clearInstancePool();
            UserEpisodeTagsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserEpisodeTagsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserEpisodeTagsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserEpisodeTagsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserEpisodeTagsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserEpisodeTagsTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserEpisodeTagsTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserEpisodeTagsTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserEpisodeTagsTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserEpisodeTagsTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildUserEpisodeTagsQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserEpisodeTagsTableMap::COL_CREATED_AT);
    }

} // UserEpisodeTagsQuery
