<?php

namespace Models\Episodes\Base;

use \Exception;
use \PDO;
use Models\Episodes\PlaylistEpisodes as ChildPlaylistEpisodes;
use Models\Episodes\PlaylistEpisodesQuery as ChildPlaylistEpisodesQuery;
use Models\Episodes\Map\PlaylistEpisodesTableMap;
use Models\Playlists\Playlists;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'playlist_episodes' table.
 *
 *
 *
 * @method     ChildPlaylistEpisodesQuery orderByPlaylistId($order = Criteria::ASC) Order by the playlist_id column
 * @method     ChildPlaylistEpisodesQuery orderByEpisodeId($order = Criteria::ASC) Order by the episode_id column
 *
 * @method     ChildPlaylistEpisodesQuery groupByPlaylistId() Group by the playlist_id column
 * @method     ChildPlaylistEpisodesQuery groupByEpisodeId() Group by the episode_id column
 *
 * @method     ChildPlaylistEpisodesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlaylistEpisodesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlaylistEpisodesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlaylistEpisodesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPlaylistEpisodesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPlaylistEpisodesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPlaylistEpisodesQuery leftJoinPlaylist($relationAlias = null) Adds a LEFT JOIN clause to the query using the Playlist relation
 * @method     ChildPlaylistEpisodesQuery rightJoinPlaylist($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Playlist relation
 * @method     ChildPlaylistEpisodesQuery innerJoinPlaylist($relationAlias = null) Adds a INNER JOIN clause to the query using the Playlist relation
 *
 * @method     ChildPlaylistEpisodesQuery joinWithPlaylist($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Playlist relation
 *
 * @method     ChildPlaylistEpisodesQuery leftJoinWithPlaylist() Adds a LEFT JOIN clause and with to the query using the Playlist relation
 * @method     ChildPlaylistEpisodesQuery rightJoinWithPlaylist() Adds a RIGHT JOIN clause and with to the query using the Playlist relation
 * @method     ChildPlaylistEpisodesQuery innerJoinWithPlaylist() Adds a INNER JOIN clause and with to the query using the Playlist relation
 *
 * @method     ChildPlaylistEpisodesQuery leftJoinEpisode($relationAlias = null) Adds a LEFT JOIN clause to the query using the Episode relation
 * @method     ChildPlaylistEpisodesQuery rightJoinEpisode($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Episode relation
 * @method     ChildPlaylistEpisodesQuery innerJoinEpisode($relationAlias = null) Adds a INNER JOIN clause to the query using the Episode relation
 *
 * @method     ChildPlaylistEpisodesQuery joinWithEpisode($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Episode relation
 *
 * @method     ChildPlaylistEpisodesQuery leftJoinWithEpisode() Adds a LEFT JOIN clause and with to the query using the Episode relation
 * @method     ChildPlaylistEpisodesQuery rightJoinWithEpisode() Adds a RIGHT JOIN clause and with to the query using the Episode relation
 * @method     ChildPlaylistEpisodesQuery innerJoinWithEpisode() Adds a INNER JOIN clause and with to the query using the Episode relation
 *
 * @method     \Models\Playlists\PlaylistsQuery|\Models\Episodes\EpisodesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlaylistEpisodes findOne(ConnectionInterface $con = null) Return the first ChildPlaylistEpisodes matching the query
 * @method     ChildPlaylistEpisodes findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlaylistEpisodes matching the query, or a new ChildPlaylistEpisodes object populated from the query conditions when no match is found
 *
 * @method     ChildPlaylistEpisodes findOneByPlaylistId(string $playlist_id) Return the first ChildPlaylistEpisodes filtered by the playlist_id column
 * @method     ChildPlaylistEpisodes findOneByEpisodeId(string $episode_id) Return the first ChildPlaylistEpisodes filtered by the episode_id column *

 * @method     ChildPlaylistEpisodes requirePk($key, ConnectionInterface $con = null) Return the ChildPlaylistEpisodes by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylistEpisodes requireOne(ConnectionInterface $con = null) Return the first ChildPlaylistEpisodes matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlaylistEpisodes requireOneByPlaylistId(string $playlist_id) Return the first ChildPlaylistEpisodes filtered by the playlist_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylistEpisodes requireOneByEpisodeId(string $episode_id) Return the first ChildPlaylistEpisodes filtered by the episode_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlaylistEpisodes[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlaylistEpisodes objects based on current ModelCriteria
 * @method     ChildPlaylistEpisodes[]|ObjectCollection findByPlaylistId(string $playlist_id) Return ChildPlaylistEpisodes objects filtered by the playlist_id column
 * @method     ChildPlaylistEpisodes[]|ObjectCollection findByEpisodeId(string $episode_id) Return ChildPlaylistEpisodes objects filtered by the episode_id column
 * @method     ChildPlaylistEpisodes[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlaylistEpisodesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Episodes\Base\PlaylistEpisodesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Episodes\\PlaylistEpisodes', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlaylistEpisodesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlaylistEpisodesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlaylistEpisodesQuery) {
            return $criteria;
        }
        $query = new ChildPlaylistEpisodesQuery();
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
     * @param array[$playlist_id, $episode_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPlaylistEpisodes|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlaylistEpisodesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PlaylistEpisodesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildPlaylistEpisodes A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT playlist_id, episode_id FROM playlist_episodes WHERE playlist_id = :p0 AND episode_id = :p1';
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
            /** @var ChildPlaylistEpisodes $obj */
            $obj = new ChildPlaylistEpisodes();
            $obj->hydrate($row);
            PlaylistEpisodesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildPlaylistEpisodes|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlaylistEpisodesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PlaylistEpisodesTableMap::COL_PLAYLIST_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PlaylistEpisodesTableMap::COL_EPISODE_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlaylistEpisodesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PlaylistEpisodesTableMap::COL_PLAYLIST_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PlaylistEpisodesTableMap::COL_EPISODE_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildPlaylistEpisodesQuery The current query, for fluid interface
     */
    public function filterByPlaylistId($playlistId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($playlistId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistEpisodesTableMap::COL_PLAYLIST_ID, $playlistId, $comparison);
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
     * @return $this|ChildPlaylistEpisodesQuery The current query, for fluid interface
     */
    public function filterByEpisodeId($episodeId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($episodeId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistEpisodesTableMap::COL_EPISODE_ID, $episodeId, $comparison);
    }

    /**
     * Filter the query by a related \Models\Playlists\Playlists object
     *
     * @param \Models\Playlists\Playlists|ObjectCollection $playlists The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlaylistEpisodesQuery The current query, for fluid interface
     */
    public function filterByPlaylist($playlists, $comparison = null)
    {
        if ($playlists instanceof \Models\Playlists\Playlists) {
            return $this
                ->addUsingAlias(PlaylistEpisodesTableMap::COL_PLAYLIST_ID, $playlists->getId(), $comparison);
        } elseif ($playlists instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlaylistEpisodesTableMap::COL_PLAYLIST_ID, $playlists->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlaylist() only accepts arguments of type \Models\Playlists\Playlists or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Playlist relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlaylistEpisodesQuery The current query, for fluid interface
     */
    public function joinPlaylist($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Playlist');

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
            $this->addJoinObject($join, 'Playlist');
        }

        return $this;
    }

    /**
     * Use the Playlist relation Playlists object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Playlists\PlaylistsQuery A secondary query class using the current class as primary query
     */
    public function usePlaylistQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlaylist($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Playlist', '\Models\Playlists\PlaylistsQuery');
    }

    /**
     * Filter the query by a related \Models\Episodes\Episodes object
     *
     * @param \Models\Episodes\Episodes|ObjectCollection $episodes The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlaylistEpisodesQuery The current query, for fluid interface
     */
    public function filterByEpisode($episodes, $comparison = null)
    {
        if ($episodes instanceof \Models\Episodes\Episodes) {
            return $this
                ->addUsingAlias(PlaylistEpisodesTableMap::COL_EPISODE_ID, $episodes->getId(), $comparison);
        } elseif ($episodes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlaylistEpisodesTableMap::COL_EPISODE_ID, $episodes->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPlaylistEpisodesQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildPlaylistEpisodes $playlistEpisodes Object to remove from the list of results
     *
     * @return $this|ChildPlaylistEpisodesQuery The current query, for fluid interface
     */
    public function prune($playlistEpisodes = null)
    {
        if ($playlistEpisodes) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PlaylistEpisodesTableMap::COL_PLAYLIST_ID), $playlistEpisodes->getPlaylistId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PlaylistEpisodesTableMap::COL_EPISODE_ID), $playlistEpisodes->getEpisodeId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the playlist_episodes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistEpisodesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlaylistEpisodesTableMap::clearInstancePool();
            PlaylistEpisodesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistEpisodesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlaylistEpisodesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlaylistEpisodesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlaylistEpisodesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlaylistEpisodesQuery
