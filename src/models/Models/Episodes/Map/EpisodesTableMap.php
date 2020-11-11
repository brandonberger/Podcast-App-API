<?php

namespace Models\Episodes\Map;

use Models\Episodes\Episodes;
use Models\Episodes\EpisodesQuery;
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
 * This class defines the structure of the 'episodes' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class EpisodesTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Episodes.Map.EpisodesTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'episodes';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\Episodes\\Episodes';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.Episodes.Episodes';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the id field
     */
    const COL_ID = 'episodes.id';

    /**
     * the column name for the episode_id field
     */
    const COL_EPISODE_ID = 'episodes.episode_id';

    /**
     * the column name for the podcast_id field
     */
    const COL_PODCAST_ID = 'episodes.podcast_id';

    /**
     * the column name for the number_of_plays field
     */
    const COL_NUMBER_OF_PLAYS = 'episodes.number_of_plays';

    /**
     * the column name for the number_of_downloads field
     */
    const COL_NUMBER_OF_DOWNLOADS = 'episodes.number_of_downloads';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'episodes.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'episodes.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'EpisodeId', 'PodcastId', 'NumberOfPlays', 'NumberOfDownloads', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'episodeId', 'podcastId', 'numberOfPlays', 'numberOfDownloads', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(EpisodesTableMap::COL_ID, EpisodesTableMap::COL_EPISODE_ID, EpisodesTableMap::COL_PODCAST_ID, EpisodesTableMap::COL_NUMBER_OF_PLAYS, EpisodesTableMap::COL_NUMBER_OF_DOWNLOADS, EpisodesTableMap::COL_CREATED_AT, EpisodesTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'episode_id', 'podcast_id', 'number_of_plays', 'number_of_downloads', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'EpisodeId' => 1, 'PodcastId' => 2, 'NumberOfPlays' => 3, 'NumberOfDownloads' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'episodeId' => 1, 'podcastId' => 2, 'numberOfPlays' => 3, 'numberOfDownloads' => 4, 'createdAt' => 5, 'updatedAt' => 6, ),
        self::TYPE_COLNAME       => array(EpisodesTableMap::COL_ID => 0, EpisodesTableMap::COL_EPISODE_ID => 1, EpisodesTableMap::COL_PODCAST_ID => 2, EpisodesTableMap::COL_NUMBER_OF_PLAYS => 3, EpisodesTableMap::COL_NUMBER_OF_DOWNLOADS => 4, EpisodesTableMap::COL_CREATED_AT => 5, EpisodesTableMap::COL_UPDATED_AT => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'episode_id' => 1, 'podcast_id' => 2, 'number_of_plays' => 3, 'number_of_downloads' => 4, 'created_at' => 5, 'updated_at' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
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
        $this->setName('episodes');
        $this->setPhpName('Episodes');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\Episodes\\Episodes');
        $this->setPackage('Models.Episodes');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'VARCHAR', true, null, null);
        $this->addColumn('episode_id', 'EpisodeId', 'VARCHAR', true, 255, null);
        $this->addForeignKey('podcast_id', 'PodcastId', 'VARCHAR', 'podcasts', 'id', true, null, null);
        $this->addColumn('number_of_plays', 'NumberOfPlays', 'INTEGER', false, null, null);
        $this->addColumn('number_of_downloads', 'NumberOfDownloads', 'INTEGER', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Podcasts', '\\Models\\Podcasts\\Podcasts', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':podcast_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('UserEpisodes', '\\Models\\Episodes\\UserEpisodes', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':episode_id',
    1 => ':id',
  ),
), null, null, 'UserEpisodess', false);
        $this->addRelation('PlaylistEpisodes', '\\Models\\Episodes\\PlaylistEpisodes', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':episode_id',
    1 => ':id',
  ),
), null, null, 'PlaylistEpisodess', false);
        $this->addRelation('Bookmarks', '\\Models\\Bookmarks\\Bookmarks', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':episode_id',
    1 => ':id',
  ),
), null, null, 'Bookmarkss', false);
        $this->addRelation('UserEpisodeTags', '\\Models\\UserTags\\UserEpisodeTags', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':episode_id',
    1 => ':id',
  ),
), null, null, 'UserEpisodeTagss', false);
        $this->addRelation('Playlist', '\\Models\\Playlists\\Playlists', RelationMap::MANY_TO_MANY, array(), null, null, 'Playlists');
        $this->addRelation('EpisodeTag', '\\Models\\Tags\\Tags', RelationMap::MANY_TO_MANY, array(), null, null, 'EpisodeTags');
        $this->addRelation('UsersEpisodeTags', '\\Models\\Users\\Users', RelationMap::MANY_TO_MANY, array(), null, null, 'UsersEpisodeTagss');
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
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
        return (string) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
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
        return $withPrefix ? EpisodesTableMap::CLASS_DEFAULT : EpisodesTableMap::OM_CLASS;
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
     * @return array           (Episodes object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = EpisodesTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EpisodesTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EpisodesTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EpisodesTableMap::OM_CLASS;
            /** @var Episodes $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EpisodesTableMap::addInstanceToPool($obj, $key);
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
            $key = EpisodesTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EpisodesTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Episodes $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EpisodesTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(EpisodesTableMap::COL_ID);
            $criteria->addSelectColumn(EpisodesTableMap::COL_EPISODE_ID);
            $criteria->addSelectColumn(EpisodesTableMap::COL_PODCAST_ID);
            $criteria->addSelectColumn(EpisodesTableMap::COL_NUMBER_OF_PLAYS);
            $criteria->addSelectColumn(EpisodesTableMap::COL_NUMBER_OF_DOWNLOADS);
            $criteria->addSelectColumn(EpisodesTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(EpisodesTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.episode_id');
            $criteria->addSelectColumn($alias . '.podcast_id');
            $criteria->addSelectColumn($alias . '.number_of_plays');
            $criteria->addSelectColumn($alias . '.number_of_downloads');
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
        return Propel::getServiceContainer()->getDatabaseMap(EpisodesTableMap::DATABASE_NAME)->getTable(EpisodesTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(EpisodesTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(EpisodesTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new EpisodesTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Episodes or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Episodes object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(EpisodesTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\Episodes\Episodes) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EpisodesTableMap::DATABASE_NAME);
            $criteria->add(EpisodesTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = EpisodesQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            EpisodesTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                EpisodesTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the episodes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return EpisodesQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Episodes or Criteria object.
     *
     * @param mixed               $criteria Criteria or Episodes object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EpisodesTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Episodes object
        }

        if ($criteria->containsKey(EpisodesTableMap::COL_ID) && $criteria->keyContainsValue(EpisodesTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EpisodesTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = EpisodesQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // EpisodesTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
EpisodesTableMap::buildTableMap();
