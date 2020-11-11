<?php

namespace Models\Tags\Base;

use \Exception;
use \PDO;
use Models\Tags\Tags as ChildTags;
use Models\Tags\TagsQuery as ChildTagsQuery;
use Models\Tags\Map\TagsTableMap;
use Models\UserTags\UserEpisodeTags;
use Models\UserTags\UserPlaylistTags;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'tags' table.
 *
 *
 *
 * @method     ChildTagsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildTagsQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildTagsQuery orderBySuperTag($order = Criteria::ASC) Order by the super_tag column
 * @method     ChildTagsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildTagsQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildTagsQuery groupById() Group by the id column
 * @method     ChildTagsQuery groupByName() Group by the name column
 * @method     ChildTagsQuery groupBySuperTag() Group by the super_tag column
 * @method     ChildTagsQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildTagsQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildTagsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTagsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTagsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTagsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildTagsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildTagsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildTagsQuery leftJoinUserPlaylistTags($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserPlaylistTags relation
 * @method     ChildTagsQuery rightJoinUserPlaylistTags($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserPlaylistTags relation
 * @method     ChildTagsQuery innerJoinUserPlaylistTags($relationAlias = null) Adds a INNER JOIN clause to the query using the UserPlaylistTags relation
 *
 * @method     ChildTagsQuery joinWithUserPlaylistTags($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserPlaylistTags relation
 *
 * @method     ChildTagsQuery leftJoinWithUserPlaylistTags() Adds a LEFT JOIN clause and with to the query using the UserPlaylistTags relation
 * @method     ChildTagsQuery rightJoinWithUserPlaylistTags() Adds a RIGHT JOIN clause and with to the query using the UserPlaylistTags relation
 * @method     ChildTagsQuery innerJoinWithUserPlaylistTags() Adds a INNER JOIN clause and with to the query using the UserPlaylistTags relation
 *
 * @method     ChildTagsQuery leftJoinUserEpisodeTags($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserEpisodeTags relation
 * @method     ChildTagsQuery rightJoinUserEpisodeTags($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserEpisodeTags relation
 * @method     ChildTagsQuery innerJoinUserEpisodeTags($relationAlias = null) Adds a INNER JOIN clause to the query using the UserEpisodeTags relation
 *
 * @method     ChildTagsQuery joinWithUserEpisodeTags($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserEpisodeTags relation
 *
 * @method     ChildTagsQuery leftJoinWithUserEpisodeTags() Adds a LEFT JOIN clause and with to the query using the UserEpisodeTags relation
 * @method     ChildTagsQuery rightJoinWithUserEpisodeTags() Adds a RIGHT JOIN clause and with to the query using the UserEpisodeTags relation
 * @method     ChildTagsQuery innerJoinWithUserEpisodeTags() Adds a INNER JOIN clause and with to the query using the UserEpisodeTags relation
 *
 * @method     \Models\UserTags\UserPlaylistTagsQuery|\Models\UserTags\UserEpisodeTagsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildTags findOne(ConnectionInterface $con = null) Return the first ChildTags matching the query
 * @method     ChildTags findOneOrCreate(ConnectionInterface $con = null) Return the first ChildTags matching the query, or a new ChildTags object populated from the query conditions when no match is found
 *
 * @method     ChildTags findOneById(string $id) Return the first ChildTags filtered by the id column
 * @method     ChildTags findOneByName(string $name) Return the first ChildTags filtered by the name column
 * @method     ChildTags findOneBySuperTag(int $super_tag) Return the first ChildTags filtered by the super_tag column
 * @method     ChildTags findOneByCreatedAt(string $created_at) Return the first ChildTags filtered by the created_at column
 * @method     ChildTags findOneByUpdatedAt(string $updated_at) Return the first ChildTags filtered by the updated_at column *

 * @method     ChildTags requirePk($key, ConnectionInterface $con = null) Return the ChildTags by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTags requireOne(ConnectionInterface $con = null) Return the first ChildTags matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTags requireOneById(string $id) Return the first ChildTags filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTags requireOneByName(string $name) Return the first ChildTags filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTags requireOneBySuperTag(int $super_tag) Return the first ChildTags filtered by the super_tag column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTags requireOneByCreatedAt(string $created_at) Return the first ChildTags filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTags requireOneByUpdatedAt(string $updated_at) Return the first ChildTags filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTags[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildTags objects based on current ModelCriteria
 * @method     ChildTags[]|ObjectCollection findById(string $id) Return ChildTags objects filtered by the id column
 * @method     ChildTags[]|ObjectCollection findByName(string $name) Return ChildTags objects filtered by the name column
 * @method     ChildTags[]|ObjectCollection findBySuperTag(int $super_tag) Return ChildTags objects filtered by the super_tag column
 * @method     ChildTags[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildTags objects filtered by the created_at column
 * @method     ChildTags[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildTags objects filtered by the updated_at column
 * @method     ChildTags[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class TagsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Tags\Base\TagsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Tags\\Tags', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTagsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTagsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildTagsQuery) {
            return $criteria;
        }
        $query = new ChildTagsQuery();
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
     * @return ChildTags|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TagsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = TagsTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildTags A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, super_tag, created_at, updated_at FROM tags WHERE id = :p0';
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
            /** @var ChildTags $obj */
            $obj = new ChildTags();
            $obj->hydrate($row);
            TagsTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildTags|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildTagsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TagsTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildTagsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TagsTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildTagsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($id)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagsTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildTagsQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagsTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the super_tag column
     *
     * Example usage:
     * <code>
     * $query->filterBySuperTag(1234); // WHERE super_tag = 1234
     * $query->filterBySuperTag(array(12, 34)); // WHERE super_tag IN (12, 34)
     * $query->filterBySuperTag(array('min' => 12)); // WHERE super_tag > 12
     * </code>
     *
     * @param     mixed $superTag The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTagsQuery The current query, for fluid interface
     */
    public function filterBySuperTag($superTag = null, $comparison = null)
    {
        if (is_array($superTag)) {
            $useMinMax = false;
            if (isset($superTag['min'])) {
                $this->addUsingAlias(TagsTableMap::COL_SUPER_TAG, $superTag['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($superTag['max'])) {
                $this->addUsingAlias(TagsTableMap::COL_SUPER_TAG, $superTag['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagsTableMap::COL_SUPER_TAG, $superTag, $comparison);
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
     * @return $this|ChildTagsQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(TagsTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(TagsTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagsTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildTagsQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(TagsTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(TagsTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagsTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\UserTags\UserPlaylistTags object
     *
     * @param \Models\UserTags\UserPlaylistTags|ObjectCollection $userPlaylistTags the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTagsQuery The current query, for fluid interface
     */
    public function filterByUserPlaylistTags($userPlaylistTags, $comparison = null)
    {
        if ($userPlaylistTags instanceof \Models\UserTags\UserPlaylistTags) {
            return $this
                ->addUsingAlias(TagsTableMap::COL_ID, $userPlaylistTags->getTagId(), $comparison);
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
     * @return $this|ChildTagsQuery The current query, for fluid interface
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
     * @return ChildTagsQuery The current query, for fluid interface
     */
    public function filterByUserEpisodeTags($userEpisodeTags, $comparison = null)
    {
        if ($userEpisodeTags instanceof \Models\UserTags\UserEpisodeTags) {
            return $this
                ->addUsingAlias(TagsTableMap::COL_ID, $userEpisodeTags->getTagId(), $comparison);
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
     * @return $this|ChildTagsQuery The current query, for fluid interface
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
     * using the user_playlist_tags table as cross reference
     *
     * @param Playlists $playlists the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTagsQuery The current query, for fluid interface
     */
    public function filterByPlaylistsTags($playlists, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPlaylistTagsQuery()
            ->filterByPlaylistsTags($playlists, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Users object
     * using the user_playlist_tags table as cross reference
     *
     * @param Users $users the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTagsQuery The current query, for fluid interface
     */
    public function filterByUsersPlaylistTags($users, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPlaylistTagsQuery()
            ->filterByUsersPlaylistTags($users, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Episodes object
     * using the user_episode_tags table as cross reference
     *
     * @param Episodes $episodes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTagsQuery The current query, for fluid interface
     */
    public function filterByEpisodesTags($episodes, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserEpisodeTagsQuery()
            ->filterByEpisodesTags($episodes, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Users object
     * using the user_episode_tags table as cross reference
     *
     * @param Users $users the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTagsQuery The current query, for fluid interface
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
     * @param   ChildTags $tags Object to remove from the list of results
     *
     * @return $this|ChildTagsQuery The current query, for fluid interface
     */
    public function prune($tags = null)
    {
        if ($tags) {
            $this->addUsingAlias(TagsTableMap::COL_ID, $tags->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the tags table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TagsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TagsTableMap::clearInstancePool();
            TagsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(TagsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TagsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TagsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TagsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildTagsQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(TagsTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildTagsQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(TagsTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildTagsQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(TagsTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildTagsQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(TagsTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildTagsQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(TagsTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildTagsQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(TagsTableMap::COL_CREATED_AT);
    }

} // TagsQuery
