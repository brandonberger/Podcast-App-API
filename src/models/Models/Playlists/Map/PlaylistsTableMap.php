<?php

namespace Models\Playlists\Map;

use Models\Playlists\Playlists;
use Models\Playlists\PlaylistsQuery;
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
 * This class defines the structure of the 'playlists' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PlaylistsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Playlists.Map.PlaylistsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'playlists';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\Playlists\\Playlists';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.Playlists.Playlists';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the id field
     */
    const COL_ID = 'playlists.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'playlists.name';

    /**
     * the column name for the is_parent field
     */
    const COL_IS_PARENT = 'playlists.is_parent';

    /**
     * the column name for the tag_generated field
     */
    const COL_TAG_GENERATED = 'playlists.tag_generated';

    /**
     * the column name for the favorites field
     */
    const COL_FAVORITES = 'playlists.favorites';

    /**
     * the column name for the shareable_status field
     */
    const COL_SHAREABLE_STATUS = 'playlists.shareable_status';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'playlists.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'playlists.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'Name', 'isParent', 'TagGenerated', 'Favorites', 'ShareableStatus', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'isParent', 'tagGenerated', 'favorites', 'shareableStatus', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(PlaylistsTableMap::COL_ID, PlaylistsTableMap::COL_NAME, PlaylistsTableMap::COL_IS_PARENT, PlaylistsTableMap::COL_TAG_GENERATED, PlaylistsTableMap::COL_FAVORITES, PlaylistsTableMap::COL_SHAREABLE_STATUS, PlaylistsTableMap::COL_CREATED_AT, PlaylistsTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'is_parent', 'tag_generated', 'favorites', 'shareable_status', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'isParent' => 2, 'TagGenerated' => 3, 'Favorites' => 4, 'ShareableStatus' => 5, 'CreatedAt' => 6, 'UpdatedAt' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'isParent' => 2, 'tagGenerated' => 3, 'favorites' => 4, 'shareableStatus' => 5, 'createdAt' => 6, 'updatedAt' => 7, ),
        self::TYPE_COLNAME       => array(PlaylistsTableMap::COL_ID => 0, PlaylistsTableMap::COL_NAME => 1, PlaylistsTableMap::COL_IS_PARENT => 2, PlaylistsTableMap::COL_TAG_GENERATED => 3, PlaylistsTableMap::COL_FAVORITES => 4, PlaylistsTableMap::COL_SHAREABLE_STATUS => 5, PlaylistsTableMap::COL_CREATED_AT => 6, PlaylistsTableMap::COL_UPDATED_AT => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'is_parent' => 2, 'tag_generated' => 3, 'favorites' => 4, 'shareable_status' => 5, 'created_at' => 6, 'updated_at' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
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
        $this->setName('playlists');
        $this->setPhpName('Playlists');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\Playlists\\Playlists');
        $this->setPackage('Models.Playlists');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'VARCHAR', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 50, null);
        $this->addColumn('is_parent', 'isParent', 'TINYINT', false, null, null);
        $this->addColumn('tag_generated', 'TagGenerated', 'TINYINT', false, null, null);
        $this->addColumn('favorites', 'Favorites', 'INTEGER', true, null, null);
        $this->addColumn('shareable_status', 'ShareableStatus', 'TINYINT', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('UserPlaylists', '\\Models\\Playlists\\UserPlaylists', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':playlist_id',
    1 => ':id',
  ),
), null, null, 'UserPlaylistss', false);
        $this->addRelation('PlaylistChildrenRelatedByParentId', '\\Models\\Playlists\\PlaylistChildren', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':parent_id',
    1 => ':id',
  ),
), null, null, 'PlaylistChildrensRelatedByParentId', false);
        $this->addRelation('PlaylistChildrenRelatedByChildId', '\\Models\\Playlists\\PlaylistChildren', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':child_id',
    1 => ':id',
  ),
), null, null, 'PlaylistChildrensRelatedByChildId', false);
        $this->addRelation('PlaylistComments', '\\Models\\Playlists\\PlaylistComments', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':playlist_id',
    1 => ':id',
  ),
), null, null, 'PlaylistCommentss', false);
        $this->addRelation('PlaylistEpisodes', '\\Models\\Episodes\\PlaylistEpisodes', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':playlist_id',
    1 => ':id',
  ),
), null, null, 'PlaylistEpisodess', false);
        $this->addRelation('UserPlaylistTags', '\\Models\\UserTags\\UserPlaylistTags', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':playlist_id',
    1 => ':id',
  ),
), null, null, 'UserPlaylistTagss', false);
        $this->addRelation('Users', '\\Models\\Users\\Users', RelationMap::MANY_TO_MANY, array(), null, null, 'Userss');
        $this->addRelation('PlaylistsChild', '\\Models\\Playlists\\Playlists', RelationMap::MANY_TO_MANY, array(), null, null, 'Playlistschildren');
        $this->addRelation('PlaylistsParent', '\\Models\\Playlists\\Playlists', RelationMap::MANY_TO_MANY, array(), null, null, 'PlaylistsParents');
        $this->addRelation('Episode', '\\Models\\Episodes\\Episodes', RelationMap::MANY_TO_MANY, array(), null, null, 'Episodes');
        $this->addRelation('PlaylistTag', '\\Models\\Tags\\Tags', RelationMap::MANY_TO_MANY, array(), null, null, 'PlaylistTags');
        $this->addRelation('UsersPlaylistTags', '\\Models\\Users\\Users', RelationMap::MANY_TO_MANY, array(), null, null, 'UsersPlaylistTagss');
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
        return $withPrefix ? PlaylistsTableMap::CLASS_DEFAULT : PlaylistsTableMap::OM_CLASS;
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
     * @return array           (Playlists object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PlaylistsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PlaylistsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PlaylistsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PlaylistsTableMap::OM_CLASS;
            /** @var Playlists $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PlaylistsTableMap::addInstanceToPool($obj, $key);
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
            $key = PlaylistsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PlaylistsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Playlists $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PlaylistsTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PlaylistsTableMap::COL_ID);
            $criteria->addSelectColumn(PlaylistsTableMap::COL_NAME);
            $criteria->addSelectColumn(PlaylistsTableMap::COL_IS_PARENT);
            $criteria->addSelectColumn(PlaylistsTableMap::COL_TAG_GENERATED);
            $criteria->addSelectColumn(PlaylistsTableMap::COL_FAVORITES);
            $criteria->addSelectColumn(PlaylistsTableMap::COL_SHAREABLE_STATUS);
            $criteria->addSelectColumn(PlaylistsTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(PlaylistsTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.is_parent');
            $criteria->addSelectColumn($alias . '.tag_generated');
            $criteria->addSelectColumn($alias . '.favorites');
            $criteria->addSelectColumn($alias . '.shareable_status');
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
        return Propel::getServiceContainer()->getDatabaseMap(PlaylistsTableMap::DATABASE_NAME)->getTable(PlaylistsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PlaylistsTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(PlaylistsTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new PlaylistsTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Playlists or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Playlists object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistsTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\Playlists\Playlists) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PlaylistsTableMap::DATABASE_NAME);
            $criteria->add(PlaylistsTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = PlaylistsQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PlaylistsTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PlaylistsTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the playlists table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PlaylistsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Playlists or Criteria object.
     *
     * @param mixed               $criteria Criteria or Playlists object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Playlists object
        }

        if ($criteria->containsKey(PlaylistsTableMap::COL_ID) && $criteria->keyContainsValue(PlaylistsTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PlaylistsTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = PlaylistsQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PlaylistsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PlaylistsTableMap::buildTableMap();
