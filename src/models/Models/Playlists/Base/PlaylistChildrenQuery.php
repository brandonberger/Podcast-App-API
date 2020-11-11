<?php

namespace Models\Playlists\Base;

use \Exception;
use \PDO;
use Models\Playlists\PlaylistChildren as ChildPlaylistChildren;
use Models\Playlists\PlaylistChildrenQuery as ChildPlaylistChildrenQuery;
use Models\Playlists\Map\PlaylistChildrenTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'playlists_children' table.
 *
 *
 *
 * @method     ChildPlaylistChildrenQuery orderByParentId($order = Criteria::ASC) Order by the parent_id column
 * @method     ChildPlaylistChildrenQuery orderByChildId($order = Criteria::ASC) Order by the child_id column
 *
 * @method     ChildPlaylistChildrenQuery groupByParentId() Group by the parent_id column
 * @method     ChildPlaylistChildrenQuery groupByChildId() Group by the child_id column
 *
 * @method     ChildPlaylistChildrenQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlaylistChildrenQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlaylistChildrenQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlaylistChildrenQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPlaylistChildrenQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPlaylistChildrenQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPlaylistChildrenQuery leftJoinPlaylistsParent($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlaylistsParent relation
 * @method     ChildPlaylistChildrenQuery rightJoinPlaylistsParent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlaylistsParent relation
 * @method     ChildPlaylistChildrenQuery innerJoinPlaylistsParent($relationAlias = null) Adds a INNER JOIN clause to the query using the PlaylistsParent relation
 *
 * @method     ChildPlaylistChildrenQuery joinWithPlaylistsParent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlaylistsParent relation
 *
 * @method     ChildPlaylistChildrenQuery leftJoinWithPlaylistsParent() Adds a LEFT JOIN clause and with to the query using the PlaylistsParent relation
 * @method     ChildPlaylistChildrenQuery rightJoinWithPlaylistsParent() Adds a RIGHT JOIN clause and with to the query using the PlaylistsParent relation
 * @method     ChildPlaylistChildrenQuery innerJoinWithPlaylistsParent() Adds a INNER JOIN clause and with to the query using the PlaylistsParent relation
 *
 * @method     ChildPlaylistChildrenQuery leftJoinPlaylistsChild($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlaylistsChild relation
 * @method     ChildPlaylistChildrenQuery rightJoinPlaylistsChild($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlaylistsChild relation
 * @method     ChildPlaylistChildrenQuery innerJoinPlaylistsChild($relationAlias = null) Adds a INNER JOIN clause to the query using the PlaylistsChild relation
 *
 * @method     ChildPlaylistChildrenQuery joinWithPlaylistsChild($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlaylistsChild relation
 *
 * @method     ChildPlaylistChildrenQuery leftJoinWithPlaylistsChild() Adds a LEFT JOIN clause and with to the query using the PlaylistsChild relation
 * @method     ChildPlaylistChildrenQuery rightJoinWithPlaylistsChild() Adds a RIGHT JOIN clause and with to the query using the PlaylistsChild relation
 * @method     ChildPlaylistChildrenQuery innerJoinWithPlaylistsChild() Adds a INNER JOIN clause and with to the query using the PlaylistsChild relation
 *
 * @method     \Models\Playlists\PlaylistsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlaylistChildren findOne(ConnectionInterface $con = null) Return the first ChildPlaylistChildren matching the query
 * @method     ChildPlaylistChildren findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlaylistChildren matching the query, or a new ChildPlaylistChildren object populated from the query conditions when no match is found
 *
 * @method     ChildPlaylistChildren findOneByParentId(string $parent_id) Return the first ChildPlaylistChildren filtered by the parent_id column
 * @method     ChildPlaylistChildren findOneByChildId(string $child_id) Return the first ChildPlaylistChildren filtered by the child_id column *

 * @method     ChildPlaylistChildren requirePk($key, ConnectionInterface $con = null) Return the ChildPlaylistChildren by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylistChildren requireOne(ConnectionInterface $con = null) Return the first ChildPlaylistChildren matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlaylistChildren requireOneByParentId(string $parent_id) Return the first ChildPlaylistChildren filtered by the parent_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylistChildren requireOneByChildId(string $child_id) Return the first ChildPlaylistChildren filtered by the child_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlaylistChildren[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlaylistChildren objects based on current ModelCriteria
 * @method     ChildPlaylistChildren[]|ObjectCollection findByParentId(string $parent_id) Return ChildPlaylistChildren objects filtered by the parent_id column
 * @method     ChildPlaylistChildren[]|ObjectCollection findByChildId(string $child_id) Return ChildPlaylistChildren objects filtered by the child_id column
 * @method     ChildPlaylistChildren[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlaylistChildrenQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Playlists\Base\PlaylistChildrenQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Playlists\\PlaylistChildren', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlaylistChildrenQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlaylistChildrenQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlaylistChildrenQuery) {
            return $criteria;
        }
        $query = new ChildPlaylistChildrenQuery();
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
     * @param array[$parent_id, $child_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPlaylistChildren|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlaylistChildrenTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PlaylistChildrenTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildPlaylistChildren A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT parent_id, child_id FROM playlists_children WHERE parent_id = :p0 AND child_id = :p1';
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
            /** @var ChildPlaylistChildren $obj */
            $obj = new ChildPlaylistChildren();
            $obj->hydrate($row);
            PlaylistChildrenTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildPlaylistChildren|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlaylistChildrenQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PlaylistChildrenTableMap::COL_PARENT_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PlaylistChildrenTableMap::COL_CHILD_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlaylistChildrenQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PlaylistChildrenTableMap::COL_PARENT_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PlaylistChildrenTableMap::COL_CHILD_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the parent_id column
     *
     * Example usage:
     * <code>
     * $query->filterByParentId('fooValue');   // WHERE parent_id = 'fooValue'
     * $query->filterByParentId('%fooValue%', Criteria::LIKE); // WHERE parent_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $parentId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlaylistChildrenQuery The current query, for fluid interface
     */
    public function filterByParentId($parentId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($parentId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistChildrenTableMap::COL_PARENT_ID, $parentId, $comparison);
    }

    /**
     * Filter the query on the child_id column
     *
     * Example usage:
     * <code>
     * $query->filterByChildId('fooValue');   // WHERE child_id = 'fooValue'
     * $query->filterByChildId('%fooValue%', Criteria::LIKE); // WHERE child_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $childId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlaylistChildrenQuery The current query, for fluid interface
     */
    public function filterByChildId($childId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($childId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistChildrenTableMap::COL_CHILD_ID, $childId, $comparison);
    }

    /**
     * Filter the query by a related \Models\Playlists\Playlists object
     *
     * @param \Models\Playlists\Playlists|ObjectCollection $playlists The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlaylistChildrenQuery The current query, for fluid interface
     */
    public function filterByPlaylistsParent($playlists, $comparison = null)
    {
        if ($playlists instanceof \Models\Playlists\Playlists) {
            return $this
                ->addUsingAlias(PlaylistChildrenTableMap::COL_PARENT_ID, $playlists->getId(), $comparison);
        } elseif ($playlists instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlaylistChildrenTableMap::COL_PARENT_ID, $playlists->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlaylistsParent() only accepts arguments of type \Models\Playlists\Playlists or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlaylistsParent relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlaylistChildrenQuery The current query, for fluid interface
     */
    public function joinPlaylistsParent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlaylistsParent');

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
            $this->addJoinObject($join, 'PlaylistsParent');
        }

        return $this;
    }

    /**
     * Use the PlaylistsParent relation Playlists object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Playlists\PlaylistsQuery A secondary query class using the current class as primary query
     */
    public function usePlaylistsParentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlaylistsParent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlaylistsParent', '\Models\Playlists\PlaylistsQuery');
    }

    /**
     * Filter the query by a related \Models\Playlists\Playlists object
     *
     * @param \Models\Playlists\Playlists|ObjectCollection $playlists The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlaylistChildrenQuery The current query, for fluid interface
     */
    public function filterByPlaylistsChild($playlists, $comparison = null)
    {
        if ($playlists instanceof \Models\Playlists\Playlists) {
            return $this
                ->addUsingAlias(PlaylistChildrenTableMap::COL_CHILD_ID, $playlists->getId(), $comparison);
        } elseif ($playlists instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlaylistChildrenTableMap::COL_CHILD_ID, $playlists->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlaylistsChild() only accepts arguments of type \Models\Playlists\Playlists or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlaylistsChild relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlaylistChildrenQuery The current query, for fluid interface
     */
    public function joinPlaylistsChild($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlaylistsChild');

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
            $this->addJoinObject($join, 'PlaylistsChild');
        }

        return $this;
    }

    /**
     * Use the PlaylistsChild relation Playlists object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Playlists\PlaylistsQuery A secondary query class using the current class as primary query
     */
    public function usePlaylistsChildQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlaylistsChild($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlaylistsChild', '\Models\Playlists\PlaylistsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlaylistChildren $playlistChildren Object to remove from the list of results
     *
     * @return $this|ChildPlaylistChildrenQuery The current query, for fluid interface
     */
    public function prune($playlistChildren = null)
    {
        if ($playlistChildren) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PlaylistChildrenTableMap::COL_PARENT_ID), $playlistChildren->getParentId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PlaylistChildrenTableMap::COL_CHILD_ID), $playlistChildren->getChildId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the playlists_children table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistChildrenTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlaylistChildrenTableMap::clearInstancePool();
            PlaylistChildrenTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistChildrenTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlaylistChildrenTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlaylistChildrenTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlaylistChildrenTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlaylistChildrenQuery
