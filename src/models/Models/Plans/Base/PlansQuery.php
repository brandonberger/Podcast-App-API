<?php

namespace Models\Plans\Base;

use \Exception;
use \PDO;
use Models\Plans\Plans as ChildPlans;
use Models\Plans\PlansQuery as ChildPlansQuery;
use Models\Plans\Map\PlansTableMap;
use Models\UserSettings\UserSettings;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'plans' table.
 *
 *
 *
 * @method     ChildPlansQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPlansQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildPlansQuery orderBySubPlaylists($order = Criteria::ASC) Order by the sub_playlists column
 * @method     ChildPlansQuery orderByAutomatedTagging($order = Criteria::ASC) Order by the automated_tagging column
 * @method     ChildPlansQuery orderByPlaylistMax($order = Criteria::ASC) Order by the playlist_max column
 *
 * @method     ChildPlansQuery groupById() Group by the id column
 * @method     ChildPlansQuery groupByName() Group by the name column
 * @method     ChildPlansQuery groupBySubPlaylists() Group by the sub_playlists column
 * @method     ChildPlansQuery groupByAutomatedTagging() Group by the automated_tagging column
 * @method     ChildPlansQuery groupByPlaylistMax() Group by the playlist_max column
 *
 * @method     ChildPlansQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlansQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlansQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlansQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPlansQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPlansQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPlansQuery leftJoinUserSettings($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserSettings relation
 * @method     ChildPlansQuery rightJoinUserSettings($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserSettings relation
 * @method     ChildPlansQuery innerJoinUserSettings($relationAlias = null) Adds a INNER JOIN clause to the query using the UserSettings relation
 *
 * @method     ChildPlansQuery joinWithUserSettings($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserSettings relation
 *
 * @method     ChildPlansQuery leftJoinWithUserSettings() Adds a LEFT JOIN clause and with to the query using the UserSettings relation
 * @method     ChildPlansQuery rightJoinWithUserSettings() Adds a RIGHT JOIN clause and with to the query using the UserSettings relation
 * @method     ChildPlansQuery innerJoinWithUserSettings() Adds a INNER JOIN clause and with to the query using the UserSettings relation
 *
 * @method     \Models\UserSettings\UserSettingsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlans findOne(ConnectionInterface $con = null) Return the first ChildPlans matching the query
 * @method     ChildPlans findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlans matching the query, or a new ChildPlans object populated from the query conditions when no match is found
 *
 * @method     ChildPlans findOneById(string $id) Return the first ChildPlans filtered by the id column
 * @method     ChildPlans findOneByName(string $name) Return the first ChildPlans filtered by the name column
 * @method     ChildPlans findOneBySubPlaylists(int $sub_playlists) Return the first ChildPlans filtered by the sub_playlists column
 * @method     ChildPlans findOneByAutomatedTagging(int $automated_tagging) Return the first ChildPlans filtered by the automated_tagging column
 * @method     ChildPlans findOneByPlaylistMax(string $playlist_max) Return the first ChildPlans filtered by the playlist_max column *

 * @method     ChildPlans requirePk($key, ConnectionInterface $con = null) Return the ChildPlans by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlans requireOne(ConnectionInterface $con = null) Return the first ChildPlans matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlans requireOneById(string $id) Return the first ChildPlans filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlans requireOneByName(string $name) Return the first ChildPlans filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlans requireOneBySubPlaylists(int $sub_playlists) Return the first ChildPlans filtered by the sub_playlists column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlans requireOneByAutomatedTagging(int $automated_tagging) Return the first ChildPlans filtered by the automated_tagging column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlans requireOneByPlaylistMax(string $playlist_max) Return the first ChildPlans filtered by the playlist_max column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlans[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlans objects based on current ModelCriteria
 * @method     ChildPlans[]|ObjectCollection findById(string $id) Return ChildPlans objects filtered by the id column
 * @method     ChildPlans[]|ObjectCollection findByName(string $name) Return ChildPlans objects filtered by the name column
 * @method     ChildPlans[]|ObjectCollection findBySubPlaylists(int $sub_playlists) Return ChildPlans objects filtered by the sub_playlists column
 * @method     ChildPlans[]|ObjectCollection findByAutomatedTagging(int $automated_tagging) Return ChildPlans objects filtered by the automated_tagging column
 * @method     ChildPlans[]|ObjectCollection findByPlaylistMax(string $playlist_max) Return ChildPlans objects filtered by the playlist_max column
 * @method     ChildPlans[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlansQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Plans\Base\PlansQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Plans\\Plans', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlansQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlansQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlansQuery) {
            return $criteria;
        }
        $query = new ChildPlansQuery();
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
     * @return ChildPlans|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlansTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PlansTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPlans A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, sub_playlists, automated_tagging, playlist_max FROM plans WHERE id = :p0';
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
            /** @var ChildPlans $obj */
            $obj = new ChildPlans();
            $obj->hydrate($row);
            PlansTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPlans|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlansQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlansTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlansQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlansTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPlansQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($id)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlansTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildPlansQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlansTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the sub_playlists column
     *
     * Example usage:
     * <code>
     * $query->filterBySubPlaylists(1234); // WHERE sub_playlists = 1234
     * $query->filterBySubPlaylists(array(12, 34)); // WHERE sub_playlists IN (12, 34)
     * $query->filterBySubPlaylists(array('min' => 12)); // WHERE sub_playlists > 12
     * </code>
     *
     * @param     mixed $subPlaylists The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlansQuery The current query, for fluid interface
     */
    public function filterBySubPlaylists($subPlaylists = null, $comparison = null)
    {
        if (is_array($subPlaylists)) {
            $useMinMax = false;
            if (isset($subPlaylists['min'])) {
                $this->addUsingAlias(PlansTableMap::COL_SUB_PLAYLISTS, $subPlaylists['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subPlaylists['max'])) {
                $this->addUsingAlias(PlansTableMap::COL_SUB_PLAYLISTS, $subPlaylists['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlansTableMap::COL_SUB_PLAYLISTS, $subPlaylists, $comparison);
    }

    /**
     * Filter the query on the automated_tagging column
     *
     * Example usage:
     * <code>
     * $query->filterByAutomatedTagging(1234); // WHERE automated_tagging = 1234
     * $query->filterByAutomatedTagging(array(12, 34)); // WHERE automated_tagging IN (12, 34)
     * $query->filterByAutomatedTagging(array('min' => 12)); // WHERE automated_tagging > 12
     * </code>
     *
     * @param     mixed $automatedTagging The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlansQuery The current query, for fluid interface
     */
    public function filterByAutomatedTagging($automatedTagging = null, $comparison = null)
    {
        if (is_array($automatedTagging)) {
            $useMinMax = false;
            if (isset($automatedTagging['min'])) {
                $this->addUsingAlias(PlansTableMap::COL_AUTOMATED_TAGGING, $automatedTagging['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($automatedTagging['max'])) {
                $this->addUsingAlias(PlansTableMap::COL_AUTOMATED_TAGGING, $automatedTagging['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlansTableMap::COL_AUTOMATED_TAGGING, $automatedTagging, $comparison);
    }

    /**
     * Filter the query on the playlist_max column
     *
     * Example usage:
     * <code>
     * $query->filterByPlaylistMax('fooValue');   // WHERE playlist_max = 'fooValue'
     * $query->filterByPlaylistMax('%fooValue%', Criteria::LIKE); // WHERE playlist_max LIKE '%fooValue%'
     * </code>
     *
     * @param     string $playlistMax The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlansQuery The current query, for fluid interface
     */
    public function filterByPlaylistMax($playlistMax = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($playlistMax)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlansTableMap::COL_PLAYLIST_MAX, $playlistMax, $comparison);
    }

    /**
     * Filter the query by a related \Models\UserSettings\UserSettings object
     *
     * @param \Models\UserSettings\UserSettings|ObjectCollection $userSettings the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlansQuery The current query, for fluid interface
     */
    public function filterByUserSettings($userSettings, $comparison = null)
    {
        if ($userSettings instanceof \Models\UserSettings\UserSettings) {
            return $this
                ->addUsingAlias(PlansTableMap::COL_ID, $userSettings->getPlanId(), $comparison);
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
     * @return $this|ChildPlansQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildPlans $plans Object to remove from the list of results
     *
     * @return $this|ChildPlansQuery The current query, for fluid interface
     */
    public function prune($plans = null)
    {
        if ($plans) {
            $this->addUsingAlias(PlansTableMap::COL_ID, $plans->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the plans table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlansTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlansTableMap::clearInstancePool();
            PlansTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlansTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlansTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlansTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlansTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlansQuery
