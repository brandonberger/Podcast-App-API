<?php

namespace Models\Podcasts\Base;

use \Exception;
use \PDO;
use Models\Episodes\Episodes;
use Models\Podcasts\Podcasts as ChildPodcasts;
use Models\Podcasts\PodcastsQuery as ChildPodcastsQuery;
use Models\Podcasts\Map\PodcastsTableMap;
use Models\UserPodcasts\UserPodcasts;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'podcasts' table.
 *
 *
 *
 * @method     ChildPodcastsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPodcastsQuery orderByPodcastId($order = Criteria::ASC) Order by the podcast_id column
 * @method     ChildPodcastsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPodcastsQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildPodcastsQuery groupById() Group by the id column
 * @method     ChildPodcastsQuery groupByPodcastId() Group by the podcast_id column
 * @method     ChildPodcastsQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPodcastsQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildPodcastsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPodcastsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPodcastsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPodcastsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPodcastsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPodcastsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPodcastsQuery leftJoinUserPodcasts($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserPodcasts relation
 * @method     ChildPodcastsQuery rightJoinUserPodcasts($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserPodcasts relation
 * @method     ChildPodcastsQuery innerJoinUserPodcasts($relationAlias = null) Adds a INNER JOIN clause to the query using the UserPodcasts relation
 *
 * @method     ChildPodcastsQuery joinWithUserPodcasts($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserPodcasts relation
 *
 * @method     ChildPodcastsQuery leftJoinWithUserPodcasts() Adds a LEFT JOIN clause and with to the query using the UserPodcasts relation
 * @method     ChildPodcastsQuery rightJoinWithUserPodcasts() Adds a RIGHT JOIN clause and with to the query using the UserPodcasts relation
 * @method     ChildPodcastsQuery innerJoinWithUserPodcasts() Adds a INNER JOIN clause and with to the query using the UserPodcasts relation
 *
 * @method     ChildPodcastsQuery leftJoinEpisodes($relationAlias = null) Adds a LEFT JOIN clause to the query using the Episodes relation
 * @method     ChildPodcastsQuery rightJoinEpisodes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Episodes relation
 * @method     ChildPodcastsQuery innerJoinEpisodes($relationAlias = null) Adds a INNER JOIN clause to the query using the Episodes relation
 *
 * @method     ChildPodcastsQuery joinWithEpisodes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Episodes relation
 *
 * @method     ChildPodcastsQuery leftJoinWithEpisodes() Adds a LEFT JOIN clause and with to the query using the Episodes relation
 * @method     ChildPodcastsQuery rightJoinWithEpisodes() Adds a RIGHT JOIN clause and with to the query using the Episodes relation
 * @method     ChildPodcastsQuery innerJoinWithEpisodes() Adds a INNER JOIN clause and with to the query using the Episodes relation
 *
 * @method     \Models\UserPodcasts\UserPodcastsQuery|\Models\Episodes\EpisodesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPodcasts findOne(ConnectionInterface $con = null) Return the first ChildPodcasts matching the query
 * @method     ChildPodcasts findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPodcasts matching the query, or a new ChildPodcasts object populated from the query conditions when no match is found
 *
 * @method     ChildPodcasts findOneById(string $id) Return the first ChildPodcasts filtered by the id column
 * @method     ChildPodcasts findOneByPodcastId(string $podcast_id) Return the first ChildPodcasts filtered by the podcast_id column
 * @method     ChildPodcasts findOneByCreatedAt(string $created_at) Return the first ChildPodcasts filtered by the created_at column
 * @method     ChildPodcasts findOneByUpdatedAt(string $updated_at) Return the first ChildPodcasts filtered by the updated_at column *

 * @method     ChildPodcasts requirePk($key, ConnectionInterface $con = null) Return the ChildPodcasts by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPodcasts requireOne(ConnectionInterface $con = null) Return the first ChildPodcasts matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPodcasts requireOneById(string $id) Return the first ChildPodcasts filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPodcasts requireOneByPodcastId(string $podcast_id) Return the first ChildPodcasts filtered by the podcast_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPodcasts requireOneByCreatedAt(string $created_at) Return the first ChildPodcasts filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPodcasts requireOneByUpdatedAt(string $updated_at) Return the first ChildPodcasts filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPodcasts[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPodcasts objects based on current ModelCriteria
 * @method     ChildPodcasts[]|ObjectCollection findById(string $id) Return ChildPodcasts objects filtered by the id column
 * @method     ChildPodcasts[]|ObjectCollection findByPodcastId(string $podcast_id) Return ChildPodcasts objects filtered by the podcast_id column
 * @method     ChildPodcasts[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildPodcasts objects filtered by the created_at column
 * @method     ChildPodcasts[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildPodcasts objects filtered by the updated_at column
 * @method     ChildPodcasts[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PodcastsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Podcasts\Base\PodcastsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Podcasts\\Podcasts', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPodcastsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPodcastsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPodcastsQuery) {
            return $criteria;
        }
        $query = new ChildPodcastsQuery();
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
     * @return ChildPodcasts|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PodcastsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PodcastsTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPodcasts A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, podcast_id, created_at, updated_at FROM podcasts WHERE id = :p0';
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
            /** @var ChildPodcasts $obj */
            $obj = new ChildPodcasts();
            $obj->hydrate($row);
            PodcastsTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPodcasts|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PodcastsTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PodcastsTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($id)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PodcastsTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function filterByPodcastId($podcastId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($podcastId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PodcastsTableMap::COL_PODCAST_ID, $podcastId, $comparison);
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
     * @return $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PodcastsTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PodcastsTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PodcastsTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PodcastsTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PodcastsTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PodcastsTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\UserPodcasts\UserPodcasts object
     *
     * @param \Models\UserPodcasts\UserPodcasts|ObjectCollection $userPodcasts the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPodcastsQuery The current query, for fluid interface
     */
    public function filterByUserPodcasts($userPodcasts, $comparison = null)
    {
        if ($userPodcasts instanceof \Models\UserPodcasts\UserPodcasts) {
            return $this
                ->addUsingAlias(PodcastsTableMap::COL_ID, $userPodcasts->getPodcastId(), $comparison);
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
     * @return $this|ChildPodcastsQuery The current query, for fluid interface
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
     * Filter the query by a related \Models\Episodes\Episodes object
     *
     * @param \Models\Episodes\Episodes|ObjectCollection $episodes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPodcastsQuery The current query, for fluid interface
     */
    public function filterByEpisodes($episodes, $comparison = null)
    {
        if ($episodes instanceof \Models\Episodes\Episodes) {
            return $this
                ->addUsingAlias(PodcastsTableMap::COL_ID, $episodes->getPodcastId(), $comparison);
        } elseif ($episodes instanceof ObjectCollection) {
            return $this
                ->useEpisodesQuery()
                ->filterByPrimaryKeys($episodes->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEpisodes() only accepts arguments of type \Models\Episodes\Episodes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Episodes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function joinEpisodes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Episodes');

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
            $this->addJoinObject($join, 'Episodes');
        }

        return $this;
    }

    /**
     * Use the Episodes relation Episodes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Episodes\EpisodesQuery A secondary query class using the current class as primary query
     */
    public function useEpisodesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEpisodes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Episodes', '\Models\Episodes\EpisodesQuery');
    }

    /**
     * Filter the query by a related Users object
     * using the user_podcasts table as cross reference
     *
     * @param Users $users the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPodcastsQuery The current query, for fluid interface
     */
    public function filterByUsers($users, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPodcastsQuery()
            ->filterByUsers($users, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPodcasts $podcasts Object to remove from the list of results
     *
     * @return $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function prune($podcasts = null)
    {
        if ($podcasts) {
            $this->addUsingAlias(PodcastsTableMap::COL_ID, $podcasts->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the podcasts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PodcastsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PodcastsTableMap::clearInstancePool();
            PodcastsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PodcastsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PodcastsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PodcastsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PodcastsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PodcastsTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PodcastsTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PodcastsTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PodcastsTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PodcastsTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildPodcastsQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PodcastsTableMap::COL_CREATED_AT);
    }

} // PodcastsQuery
