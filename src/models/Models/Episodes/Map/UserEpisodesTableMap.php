<?php

namespace Models\Episodes\Map;

use Models\Episodes\UserEpisodes;
use Models\Episodes\UserEpisodesQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'user_episodes' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class UserEpisodesTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Episodes.Map.UserEpisodesTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'user_episodes';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\Episodes\\UserEpisodes';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.Episodes.UserEpisodes';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'user_episodes.user_id';

    /**
     * the column name for the episode_id field
     */
    const COL_EPISODE_ID = 'user_episodes.episode_id';

    /**
     * the column name for the downloaded field
     */
    const COL_DOWNLOADED = 'user_episodes.downloaded';

    /**
     * the column name for the saved field
     */
    const COL_SAVED = 'user_episodes.saved';

    /**
     * the column name for the last_progress field
     */
    const COL_LAST_PROGRESS = 'user_episodes.last_progress';

    /**
     * the column name for the last_played field
     */
    const COL_LAST_PLAYED = 'user_episodes.last_played';

    /**
     * the column name for the bookmark_id field
     */
    const COL_BOOKMARK_ID = 'user_episodes.bookmark_id';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'user_episodes.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'user_episodes.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('UserId', 'EpisodeId', 'Downloaded', 'Saved', 'LastProgress', 'LastPlayed', 'BookmarkId', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('userId', 'episodeId', 'downloaded', 'saved', 'lastProgress', 'lastPlayed', 'bookmarkId', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(UserEpisodesTableMap::COL_USER_ID, UserEpisodesTableMap::COL_EPISODE_ID, UserEpisodesTableMap::COL_DOWNLOADED, UserEpisodesTableMap::COL_SAVED, UserEpisodesTableMap::COL_LAST_PROGRESS, UserEpisodesTableMap::COL_LAST_PLAYED, UserEpisodesTableMap::COL_BOOKMARK_ID, UserEpisodesTableMap::COL_CREATED_AT, UserEpisodesTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('user_id', 'episode_id', 'downloaded', 'saved', 'last_progress', 'last_played', 'bookmark_id', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('UserId' => 0, 'EpisodeId' => 1, 'Downloaded' => 2, 'Saved' => 3, 'LastProgress' => 4, 'LastPlayed' => 5, 'BookmarkId' => 6, 'CreatedAt' => 7, 'UpdatedAt' => 8, ),
        self::TYPE_CAMELNAME     => array('userId' => 0, 'episodeId' => 1, 'downloaded' => 2, 'saved' => 3, 'lastProgress' => 4, 'lastPlayed' => 5, 'bookmarkId' => 6, 'createdAt' => 7, 'updatedAt' => 8, ),
        self::TYPE_COLNAME       => array(UserEpisodesTableMap::COL_USER_ID => 0, UserEpisodesTableMap::COL_EPISODE_ID => 1, UserEpisodesTableMap::COL_DOWNLOADED => 2, UserEpisodesTableMap::COL_SAVED => 3, UserEpisodesTableMap::COL_LAST_PROGRESS => 4, UserEpisodesTableMap::COL_LAST_PLAYED => 5, UserEpisodesTableMap::COL_BOOKMARK_ID => 6, UserEpisodesTableMap::COL_CREATED_AT => 7, UserEpisodesTableMap::COL_UPDATED_AT => 8, ),
        self::TYPE_FIELDNAME     => array('user_id' => 0, 'episode_id' => 1, 'downloaded' => 2, 'saved' => 3, 'last_progress' => 4, 'last_played' => 5, 'bookmark_id' => 6, 'created_at' => 7, 'updated_at' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('user_episodes');
        $this->setPhpName('UserEpisodes');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\Episodes\\UserEpisodes');
        $this->setPackage('Models.Episodes');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('user_id', 'UserId', 'VARCHAR' , 'users', 'id', true, null, null);
        $this->addForeignPrimaryKey('episode_id', 'EpisodeId', 'VARCHAR' , 'episodes', 'id', true, null, null);
        $this->addColumn('downloaded', 'Downloaded', 'TINYINT', false, null, null);
        $this->addColumn('saved', 'Saved', 'TINYINT', false, null, null);
        $this->addColumn('last_progress', 'LastProgress', 'VARCHAR', false, 50, null);
        $this->addColumn('last_played', 'LastPlayed', 'VARCHAR', false, 50, null);
        $this->addForeignKey('bookmark_id', 'BookmarkId', 'VARCHAR', 'bookmarks', 'id', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', '\\Models\\Users\\Users', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Episode', '\\Models\\Episodes\\Episodes', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':episode_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Bookmark', '\\Models\\Bookmarks\\Bookmarks', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':bookmark_id',
    1 => ':id',
  ),
), null, null, null, false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \Models\Episodes\UserEpisodes $obj A \Models\Episodes\UserEpisodes object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getUserId() || is_scalar($obj->getUserId()) || is_callable([$obj->getUserId(), '__toString']) ? (string) $obj->getUserId() : $obj->getUserId()), (null === $obj->getEpisodeId() || is_scalar($obj->getEpisodeId()) || is_callable([$obj->getEpisodeId(), '__toString']) ? (string) $obj->getEpisodeId() : $obj->getEpisodeId())]);
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \Models\Episodes\UserEpisodes object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \Models\Episodes\UserEpisodes) {
                $key = serialize([(null === $value->getUserId() || is_scalar($value->getUserId()) || is_callable([$value->getUserId(), '__toString']) ? (string) $value->getUserId() : $value->getUserId()), (null === $value->getEpisodeId() || is_scalar($value->getEpisodeId()) || is_callable([$value->getEpisodeId(), '__toString']) ? (string) $value->getEpisodeId() : $value->getEpisodeId())]);

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \Models\Episodes\UserEpisodes object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('EpisodeId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('EpisodeId', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('EpisodeId', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('EpisodeId', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('EpisodeId', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('EpisodeId', TableMap::TYPE_PHPNAME, $indexType)])]);
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
            $pks = [];

        $pks[] = (string) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (string) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('EpisodeId', TableMap::TYPE_PHPNAME, $indexType)
        ];

        return $pks;
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? UserEpisodesTableMap::CLASS_DEFAULT : UserEpisodesTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (UserEpisodes object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = UserEpisodesTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = UserEpisodesTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + UserEpisodesTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UserEpisodesTableMap::OM_CLASS;
            /** @var UserEpisodes $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            UserEpisodesTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = UserEpisodesTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = UserEpisodesTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var UserEpisodes $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UserEpisodesTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(UserEpisodesTableMap::COL_USER_ID);
            $criteria->addSelectColumn(UserEpisodesTableMap::COL_EPISODE_ID);
            $criteria->addSelectColumn(UserEpisodesTableMap::COL_DOWNLOADED);
            $criteria->addSelectColumn(UserEpisodesTableMap::COL_SAVED);
            $criteria->addSelectColumn(UserEpisodesTableMap::COL_LAST_PROGRESS);
            $criteria->addSelectColumn(UserEpisodesTableMap::COL_LAST_PLAYED);
            $criteria->addSelectColumn(UserEpisodesTableMap::COL_BOOKMARK_ID);
            $criteria->addSelectColumn(UserEpisodesTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(UserEpisodesTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.episode_id');
            $criteria->addSelectColumn($alias . '.downloaded');
            $criteria->addSelectColumn($alias . '.saved');
            $criteria->addSelectColumn($alias . '.last_progress');
            $criteria->addSelectColumn($alias . '.last_played');
            $criteria->addSelectColumn($alias . '.bookmark_id');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(UserEpisodesTableMap::DATABASE_NAME)->getTable(UserEpisodesTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(UserEpisodesTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(UserEpisodesTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new UserEpisodesTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a UserEpisodes or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or UserEpisodes object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserEpisodesTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\Episodes\UserEpisodes) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UserEpisodesTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(UserEpisodesTableMap::COL_USER_ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(UserEpisodesTableMap::COL_EPISODE_ID, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = UserEpisodesQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            UserEpisodesTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                UserEpisodesTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the user_episodes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return UserEpisodesQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a UserEpisodes or Criteria object.
     *
     * @param mixed               $criteria Criteria or UserEpisodes object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserEpisodesTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from UserEpisodes object
        }


        // Set the correct dbName
        $query = UserEpisodesQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // UserEpisodesTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
UserEpisodesTableMap::buildTableMap();
