<?php

namespace Models\Playlists\Base;

use \DateTime;
use \Exception;
use \PDO;
use Models\Episodes\Episodes;
use Models\Episodes\EpisodesQuery;
use Models\Episodes\PlaylistEpisodes;
use Models\Episodes\PlaylistEpisodesQuery;
use Models\Episodes\Base\PlaylistEpisodes as BasePlaylistEpisodes;
use Models\Episodes\Map\PlaylistEpisodesTableMap;
use Models\Playlists\PlaylistChildren as ChildPlaylistChildren;
use Models\Playlists\PlaylistChildrenQuery as ChildPlaylistChildrenQuery;
use Models\Playlists\PlaylistComments as ChildPlaylistComments;
use Models\Playlists\PlaylistCommentsQuery as ChildPlaylistCommentsQuery;
use Models\Playlists\Playlists as ChildPlaylists;
use Models\Playlists\PlaylistsQuery as ChildPlaylistsQuery;
use Models\Playlists\UserPlaylists as ChildUserPlaylists;
use Models\Playlists\UserPlaylistsQuery as ChildUserPlaylistsQuery;
use Models\Playlists\Map\PlaylistChildrenTableMap;
use Models\Playlists\Map\PlaylistCommentsTableMap;
use Models\Playlists\Map\PlaylistsTableMap;
use Models\Playlists\Map\UserPlaylistsTableMap;
use Models\Tags\Tags;
use Models\UserTags\UserPlaylistTags;
use Models\UserTags\UserPlaylistTagsQuery;
use Models\UserTags\Base\UserPlaylistTags as BaseUserPlaylistTags;
use Models\UserTags\Map\UserPlaylistTagsTableMap;
use Models\Users\Users;
use Models\Users\UsersQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Collection\ObjectCombinationCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'playlists' table.
 *
 *
 *
 * @package    propel.generator.Models.Playlists.Base
 */
abstract class Playlists implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Playlists\\Map\\PlaylistsTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        string
     */
    protected $id;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the is_parent field.
     *
     * @var        int
     */
    protected $is_parent;

    /**
     * The value for the tag_generated field.
     *
     * @var        int
     */
    protected $tag_generated;

    /**
     * The value for the favorites field.
     *
     * @var        int
     */
    protected $favorites;

    /**
     * The value for the shareable_status field.
     *
     * @var        int
     */
    protected $shareable_status;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime
     */
    protected $updated_at;

    /**
     * @var        ObjectCollection|ChildUserPlaylists[] Collection to store aggregation of ChildUserPlaylists objects.
     */
    protected $collUserPlaylistss;
    protected $collUserPlaylistssPartial;

    /**
     * @var        ObjectCollection|ChildPlaylistChildren[] Collection to store aggregation of ChildPlaylistChildren objects.
     */
    protected $collPlaylistChildrensRelatedByParentId;
    protected $collPlaylistChildrensRelatedByParentIdPartial;

    /**
     * @var        ObjectCollection|ChildPlaylistChildren[] Collection to store aggregation of ChildPlaylistChildren objects.
     */
    protected $collPlaylistChildrensRelatedByChildId;
    protected $collPlaylistChildrensRelatedByChildIdPartial;

    /**
     * @var        ObjectCollection|ChildPlaylistComments[] Collection to store aggregation of ChildPlaylistComments objects.
     */
    protected $collPlaylistCommentss;
    protected $collPlaylistCommentssPartial;

    /**
     * @var        ObjectCollection|PlaylistEpisodes[] Collection to store aggregation of PlaylistEpisodes objects.
     */
    protected $collPlaylistEpisodess;
    protected $collPlaylistEpisodessPartial;

    /**
     * @var        ObjectCollection|UserPlaylistTags[] Collection to store aggregation of UserPlaylistTags objects.
     */
    protected $collUserPlaylistTagss;
    protected $collUserPlaylistTagssPartial;

    /**
     * @var        ObjectCollection|Users[] Cross Collection to store aggregation of Users objects.
     */
    protected $collUserss;

    /**
     * @var bool
     */
    protected $collUserssPartial;

    /**
     * @var        ObjectCollection|ChildPlaylists[] Cross Collection to store aggregation of ChildPlaylists objects.
     */
    protected $collPlaylistschildren;

    /**
     * @var bool
     */
    protected $collPlaylistschildrenPartial;

    /**
     * @var        ObjectCollection|ChildPlaylists[] Cross Collection to store aggregation of ChildPlaylists objects.
     */
    protected $collPlaylistsParents;

    /**
     * @var bool
     */
    protected $collPlaylistsParentsPartial;

    /**
     * @var        ObjectCollection|Episodes[] Cross Collection to store aggregation of Episodes objects.
     */
    protected $collEpisodes;

    /**
     * @var bool
     */
    protected $collEpisodesPartial;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildTags, ChildUsers combination combinations.
     */
    protected $combinationCollPlaylistTagUsersPlaylistTagss;

    /**
     * @var bool
     */
    protected $combinationCollPlaylistTagUsersPlaylistTagssPartial;

    /**
     * @var        ObjectCollection|Tags[] Cross Collection to store aggregation of Tags objects.
     */
    protected $collPlaylistTags;

    /**
     * @var bool
     */
    protected $collPlaylistTagsPartial;

    /**
     * @var        ObjectCollection|Users[] Cross Collection to store aggregation of Users objects.
     */
    protected $collUsersPlaylistTagss;

    /**
     * @var bool
     */
    protected $collUsersPlaylistTagssPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Users[]
     */
    protected $userssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlaylists[]
     */
    protected $playlistschildrenScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlaylists[]
     */
    protected $playlistsParentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Episodes[]
     */
    protected $episodesScheduledForDeletion = null;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildTags, ChildUsers combination combinations.
     */
    protected $combinationCollPlaylistTagUsersPlaylistTagssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserPlaylists[]
     */
    protected $userPlaylistssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlaylistChildren[]
     */
    protected $playlistChildrensRelatedByParentIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlaylistChildren[]
     */
    protected $playlistChildrensRelatedByChildIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlaylistComments[]
     */
    protected $playlistCommentssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|PlaylistEpisodes[]
     */
    protected $playlistEpisodessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|UserPlaylistTags[]
     */
    protected $userPlaylistTagssScheduledForDeletion = null;

    /**
     * Initializes internal state of Models\Playlists\Base\Playlists object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Playlists</code> instance.  If
     * <code>obj</code> is an instance of <code>Playlists</code>, delegates to
     * <code>equals(Playlists)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Playlists The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [is_parent] column value.
     *
     * @return int
     */
    public function getisParent()
    {
        return $this->is_parent;
    }

    /**
     * Get the [tag_generated] column value.
     *
     * @return int
     */
    public function getTagGenerated()
    {
        return $this->tag_generated;
    }

    /**
     * Get the [favorites] column value.
     *
     * @return int
     */
    public function getFavorites()
    {
        return $this->favorites;
    }

    /**
     * Get the [shareable_status] column value.
     *
     * @return int
     */
    public function getShareableStatus()
    {
        return $this->shareable_status;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTimeInterface ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param string $v new value
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PlaylistsTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[PlaylistsTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [is_parent] column.
     *
     * @param int $v new value
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function setisParent($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->is_parent !== $v) {
            $this->is_parent = $v;
            $this->modifiedColumns[PlaylistsTableMap::COL_IS_PARENT] = true;
        }

        return $this;
    } // setisParent()

    /**
     * Set the value of [tag_generated] column.
     *
     * @param int $v new value
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function setTagGenerated($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->tag_generated !== $v) {
            $this->tag_generated = $v;
            $this->modifiedColumns[PlaylistsTableMap::COL_TAG_GENERATED] = true;
        }

        return $this;
    } // setTagGenerated()

    /**
     * Set the value of [favorites] column.
     *
     * @param int $v new value
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function setFavorites($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->favorites !== $v) {
            $this->favorites = $v;
            $this->modifiedColumns[PlaylistsTableMap::COL_FAVORITES] = true;
        }

        return $this;
    } // setFavorites()

    /**
     * Set the value of [shareable_status] column.
     *
     * @param int $v new value
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function setShareableStatus($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shareable_status !== $v) {
            $this->shareable_status = $v;
            $this->modifiedColumns[PlaylistsTableMap::COL_SHAREABLE_STATUS] = true;
        }

        return $this;
    } // setShareableStatus()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlaylistsTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlaylistsTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PlaylistsTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PlaylistsTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PlaylistsTableMap::translateFieldName('isParent', TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_parent = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PlaylistsTableMap::translateFieldName('TagGenerated', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tag_generated = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PlaylistsTableMap::translateFieldName('Favorites', TableMap::TYPE_PHPNAME, $indexType)];
            $this->favorites = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : PlaylistsTableMap::translateFieldName('ShareableStatus', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shareable_status = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : PlaylistsTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : PlaylistsTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 8; // 8 = PlaylistsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Playlists\\Playlists'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlaylistsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPlaylistsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collUserPlaylistss = null;

            $this->collPlaylistChildrensRelatedByParentId = null;

            $this->collPlaylistChildrensRelatedByChildId = null;

            $this->collPlaylistCommentss = null;

            $this->collPlaylistEpisodess = null;

            $this->collUserPlaylistTagss = null;

            $this->collUserss = null;
            $this->collPlaylistschildren = null;
            $this->collPlaylistsParents = null;
            $this->collEpisodes = null;
            $this->collPlaylistTagUsersPlaylistTagss = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Playlists::setDeleted()
     * @see Playlists::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPlaylistsQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistsTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(PlaylistsTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(PlaylistsTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PlaylistsTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                PlaylistsTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->userssScheduledForDeletion !== null) {
                if (!$this->userssScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->userssScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\Playlists\UserPlaylistsQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->userssScheduledForDeletion = null;
                }

            }

            if ($this->collUserss) {
                foreach ($this->collUserss as $users) {
                    if (!$users->isDeleted() && ($users->isNew() || $users->isModified())) {
                        $users->save($con);
                    }
                }
            }


            if ($this->playlistschildrenScheduledForDeletion !== null) {
                if (!$this->playlistschildrenScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->playlistschildrenScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\Playlists\PlaylistChildrenQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->playlistschildrenScheduledForDeletion = null;
                }

            }

            if ($this->collPlaylistschildren) {
                foreach ($this->collPlaylistschildren as $playlistsChild) {
                    if (!$playlistsChild->isDeleted() && ($playlistsChild->isNew() || $playlistsChild->isModified())) {
                        $playlistsChild->save($con);
                    }
                }
            }


            if ($this->playlistsParentsScheduledForDeletion !== null) {
                if (!$this->playlistsParentsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->playlistsParentsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\Playlists\PlaylistChildrenQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->playlistsParentsScheduledForDeletion = null;
                }

            }

            if ($this->collPlaylistsParents) {
                foreach ($this->collPlaylistsParents as $playlistsParent) {
                    if (!$playlistsParent->isDeleted() && ($playlistsParent->isNew() || $playlistsParent->isModified())) {
                        $playlistsParent->save($con);
                    }
                }
            }


            if ($this->episodesScheduledForDeletion !== null) {
                if (!$this->episodesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->episodesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\Episodes\PlaylistEpisodesQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->episodesScheduledForDeletion = null;
                }

            }

            if ($this->collEpisodes) {
                foreach ($this->collEpisodes as $episode) {
                    if (!$episode->isDeleted() && ($episode->isNew() || $episode->isModified())) {
                        $episode->save($con);
                    }
                }
            }


            if ($this->combinationCollPlaylistTagUsersPlaylistTagssScheduledForDeletion !== null) {
                if (!$this->combinationCollPlaylistTagUsersPlaylistTagssScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->combinationCollPlaylistTagUsersPlaylistTagssScheduledForDeletion as $combination) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $combination[0]->getId();
                        $entryPk[2] = $combination[1]->getId();

                        $pks[] = $entryPk;
                    }

                    \Models\UserTags\UserPlaylistTagsQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->combinationCollPlaylistTagUsersPlaylistTagssScheduledForDeletion = null;
                }

            }

            if (null !== $this->combinationCollPlaylistTagUsersPlaylistTagss) {
                foreach ($this->combinationCollPlaylistTagUsersPlaylistTagss as $combination) {

                    //$combination[0] = Tags (user_playlist_tags_fk_6bac06)
                    if (!$combination[0]->isDeleted() && ($combination[0]->isNew() || $combination[0]->isModified())) {
                        $combination[0]->save($con);
                    }

                    //$combination[1] = Users (user_playlist_tags_fk_69bd79)
                    if (!$combination[1]->isDeleted() && ($combination[1]->isNew() || $combination[1]->isModified())) {
                        $combination[1]->save($con);
                    }

                }
            }


            if ($this->userPlaylistssScheduledForDeletion !== null) {
                if (!$this->userPlaylistssScheduledForDeletion->isEmpty()) {
                    \Models\Playlists\UserPlaylistsQuery::create()
                        ->filterByPrimaryKeys($this->userPlaylistssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userPlaylistssScheduledForDeletion = null;
                }
            }

            if ($this->collUserPlaylistss !== null) {
                foreach ($this->collUserPlaylistss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playlistChildrensRelatedByParentIdScheduledForDeletion !== null) {
                if (!$this->playlistChildrensRelatedByParentIdScheduledForDeletion->isEmpty()) {
                    \Models\Playlists\PlaylistChildrenQuery::create()
                        ->filterByPrimaryKeys($this->playlistChildrensRelatedByParentIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playlistChildrensRelatedByParentIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlaylistChildrensRelatedByParentId !== null) {
                foreach ($this->collPlaylistChildrensRelatedByParentId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playlistChildrensRelatedByChildIdScheduledForDeletion !== null) {
                if (!$this->playlistChildrensRelatedByChildIdScheduledForDeletion->isEmpty()) {
                    \Models\Playlists\PlaylistChildrenQuery::create()
                        ->filterByPrimaryKeys($this->playlistChildrensRelatedByChildIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playlistChildrensRelatedByChildIdScheduledForDeletion = null;
                }
            }

            if ($this->collPlaylistChildrensRelatedByChildId !== null) {
                foreach ($this->collPlaylistChildrensRelatedByChildId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playlistCommentssScheduledForDeletion !== null) {
                if (!$this->playlistCommentssScheduledForDeletion->isEmpty()) {
                    \Models\Playlists\PlaylistCommentsQuery::create()
                        ->filterByPrimaryKeys($this->playlistCommentssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playlistCommentssScheduledForDeletion = null;
                }
            }

            if ($this->collPlaylistCommentss !== null) {
                foreach ($this->collPlaylistCommentss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->playlistEpisodessScheduledForDeletion !== null) {
                if (!$this->playlistEpisodessScheduledForDeletion->isEmpty()) {
                    \Models\Episodes\PlaylistEpisodesQuery::create()
                        ->filterByPrimaryKeys($this->playlistEpisodessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->playlistEpisodessScheduledForDeletion = null;
                }
            }

            if ($this->collPlaylistEpisodess !== null) {
                foreach ($this->collPlaylistEpisodess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userPlaylistTagssScheduledForDeletion !== null) {
                if (!$this->userPlaylistTagssScheduledForDeletion->isEmpty()) {
                    \Models\UserTags\UserPlaylistTagsQuery::create()
                        ->filterByPrimaryKeys($this->userPlaylistTagssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userPlaylistTagssScheduledForDeletion = null;
                }
            }

            if ($this->collUserPlaylistTagss !== null) {
                foreach ($this->collUserPlaylistTagss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[PlaylistsTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PlaylistsTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PlaylistsTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_IS_PARENT)) {
            $modifiedColumns[':p' . $index++]  = 'is_parent';
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_TAG_GENERATED)) {
            $modifiedColumns[':p' . $index++]  = 'tag_generated';
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_FAVORITES)) {
            $modifiedColumns[':p' . $index++]  = 'favorites';
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_SHAREABLE_STATUS)) {
            $modifiedColumns[':p' . $index++]  = 'shareable_status';
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO playlists (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_STR);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'is_parent':
                        $stmt->bindValue($identifier, $this->is_parent, PDO::PARAM_INT);
                        break;
                    case 'tag_generated':
                        $stmt->bindValue($identifier, $this->tag_generated, PDO::PARAM_INT);
                        break;
                    case 'favorites':
                        $stmt->bindValue($identifier, $this->favorites, PDO::PARAM_INT);
                        break;
                    case 'shareable_status':
                        $stmt->bindValue($identifier, $this->shareable_status, PDO::PARAM_INT);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PlaylistsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getisParent();
                break;
            case 3:
                return $this->getTagGenerated();
                break;
            case 4:
                return $this->getFavorites();
                break;
            case 5:
                return $this->getShareableStatus();
                break;
            case 6:
                return $this->getCreatedAt();
                break;
            case 7:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Playlists'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Playlists'][$this->hashCode()] = true;
        $keys = PlaylistsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getisParent(),
            $keys[3] => $this->getTagGenerated(),
            $keys[4] => $this->getFavorites(),
            $keys[5] => $this->getShareableStatus(),
            $keys[6] => $this->getCreatedAt(),
            $keys[7] => $this->getUpdatedAt(),
        );
        if ($result[$keys[6]] instanceof \DateTimeInterface) {
            $result[$keys[6]] = $result[$keys[6]]->format('c');
        }

        if ($result[$keys[7]] instanceof \DateTimeInterface) {
            $result[$keys[7]] = $result[$keys[7]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collUserPlaylistss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userPlaylistss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_playlistss';
                        break;
                    default:
                        $key = 'UserPlaylistss';
                }

                $result[$key] = $this->collUserPlaylistss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlaylistChildrensRelatedByParentId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playlistChildrens';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playlists_childrens';
                        break;
                    default:
                        $key = 'PlaylistChildrens';
                }

                $result[$key] = $this->collPlaylistChildrensRelatedByParentId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlaylistChildrensRelatedByChildId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playlistChildrens';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playlists_childrens';
                        break;
                    default:
                        $key = 'PlaylistChildrens';
                }

                $result[$key] = $this->collPlaylistChildrensRelatedByChildId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlaylistCommentss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playlistCommentss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playlist_commentss';
                        break;
                    default:
                        $key = 'PlaylistCommentss';
                }

                $result[$key] = $this->collPlaylistCommentss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPlaylistEpisodess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'playlistEpisodess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'playlist_episodess';
                        break;
                    default:
                        $key = 'PlaylistEpisodess';
                }

                $result[$key] = $this->collPlaylistEpisodess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserPlaylistTagss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userPlaylistTagss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_playlist_tagss';
                        break;
                    default:
                        $key = 'UserPlaylistTagss';
                }

                $result[$key] = $this->collUserPlaylistTagss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Models\Playlists\Playlists
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PlaylistsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\Playlists\Playlists
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setisParent($value);
                break;
            case 3:
                $this->setTagGenerated($value);
                break;
            case 4:
                $this->setFavorites($value);
                break;
            case 5:
                $this->setShareableStatus($value);
                break;
            case 6:
                $this->setCreatedAt($value);
                break;
            case 7:
                $this->setUpdatedAt($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = PlaylistsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setisParent($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setTagGenerated($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setFavorites($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setShareableStatus($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setCreatedAt($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setUpdatedAt($arr[$keys[7]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Models\Playlists\Playlists The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PlaylistsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PlaylistsTableMap::COL_ID)) {
            $criteria->add(PlaylistsTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_NAME)) {
            $criteria->add(PlaylistsTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_IS_PARENT)) {
            $criteria->add(PlaylistsTableMap::COL_IS_PARENT, $this->is_parent);
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_TAG_GENERATED)) {
            $criteria->add(PlaylistsTableMap::COL_TAG_GENERATED, $this->tag_generated);
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_FAVORITES)) {
            $criteria->add(PlaylistsTableMap::COL_FAVORITES, $this->favorites);
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_SHAREABLE_STATUS)) {
            $criteria->add(PlaylistsTableMap::COL_SHAREABLE_STATUS, $this->shareable_status);
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_CREATED_AT)) {
            $criteria->add(PlaylistsTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(PlaylistsTableMap::COL_UPDATED_AT)) {
            $criteria->add(PlaylistsTableMap::COL_UPDATED_AT, $this->updated_at);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildPlaylistsQuery::create();
        $criteria->add(PlaylistsTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       string $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Models\Playlists\Playlists (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setisParent($this->getisParent());
        $copyObj->setTagGenerated($this->getTagGenerated());
        $copyObj->setFavorites($this->getFavorites());
        $copyObj->setShareableStatus($this->getShareableStatus());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUserPlaylistss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserPlaylists($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlaylistChildrensRelatedByParentId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlaylistChildrenRelatedByParentId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlaylistChildrensRelatedByChildId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlaylistChildrenRelatedByChildId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlaylistCommentss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlaylistComments($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlaylistEpisodess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlaylistEpisodes($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserPlaylistTagss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserPlaylistTags($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Models\Playlists\Playlists Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('UserPlaylists' == $relationName) {
            $this->initUserPlaylistss();
            return;
        }
        if ('PlaylistChildrenRelatedByParentId' == $relationName) {
            $this->initPlaylistChildrensRelatedByParentId();
            return;
        }
        if ('PlaylistChildrenRelatedByChildId' == $relationName) {
            $this->initPlaylistChildrensRelatedByChildId();
            return;
        }
        if ('PlaylistComments' == $relationName) {
            $this->initPlaylistCommentss();
            return;
        }
        if ('PlaylistEpisodes' == $relationName) {
            $this->initPlaylistEpisodess();
            return;
        }
        if ('UserPlaylistTags' == $relationName) {
            $this->initUserPlaylistTagss();
            return;
        }
    }

    /**
     * Clears out the collUserPlaylistss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserPlaylistss()
     */
    public function clearUserPlaylistss()
    {
        $this->collUserPlaylistss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserPlaylistss collection loaded partially.
     */
    public function resetPartialUserPlaylistss($v = true)
    {
        $this->collUserPlaylistssPartial = $v;
    }

    /**
     * Initializes the collUserPlaylistss collection.
     *
     * By default this just sets the collUserPlaylistss collection to an empty array (like clearcollUserPlaylistss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserPlaylistss($overrideExisting = true)
    {
        if (null !== $this->collUserPlaylistss && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserPlaylistsTableMap::getTableMap()->getCollectionClassName();

        $this->collUserPlaylistss = new $collectionClassName;
        $this->collUserPlaylistss->setModel('\Models\Playlists\UserPlaylists');
    }

    /**
     * Gets an array of ChildUserPlaylists objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlaylists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserPlaylists[] List of ChildUserPlaylists objects
     * @throws PropelException
     */
    public function getUserPlaylistss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserPlaylistssPartial && !$this->isNew();
        if (null === $this->collUserPlaylistss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserPlaylistss) {
                // return empty collection
                $this->initUserPlaylistss();
            } else {
                $collUserPlaylistss = ChildUserPlaylistsQuery::create(null, $criteria)
                    ->filterByPlaylists($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserPlaylistssPartial && count($collUserPlaylistss)) {
                        $this->initUserPlaylistss(false);

                        foreach ($collUserPlaylistss as $obj) {
                            if (false == $this->collUserPlaylistss->contains($obj)) {
                                $this->collUserPlaylistss->append($obj);
                            }
                        }

                        $this->collUserPlaylistssPartial = true;
                    }

                    return $collUserPlaylistss;
                }

                if ($partial && $this->collUserPlaylistss) {
                    foreach ($this->collUserPlaylistss as $obj) {
                        if ($obj->isNew()) {
                            $collUserPlaylistss[] = $obj;
                        }
                    }
                }

                $this->collUserPlaylistss = $collUserPlaylistss;
                $this->collUserPlaylistssPartial = false;
            }
        }

        return $this->collUserPlaylistss;
    }

    /**
     * Sets a collection of ChildUserPlaylists objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userPlaylistss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function setUserPlaylistss(Collection $userPlaylistss, ConnectionInterface $con = null)
    {
        /** @var ChildUserPlaylists[] $userPlaylistssToDelete */
        $userPlaylistssToDelete = $this->getUserPlaylistss(new Criteria(), $con)->diff($userPlaylistss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userPlaylistssScheduledForDeletion = clone $userPlaylistssToDelete;

        foreach ($userPlaylistssToDelete as $userPlaylistsRemoved) {
            $userPlaylistsRemoved->setPlaylists(null);
        }

        $this->collUserPlaylistss = null;
        foreach ($userPlaylistss as $userPlaylists) {
            $this->addUserPlaylists($userPlaylists);
        }

        $this->collUserPlaylistss = $userPlaylistss;
        $this->collUserPlaylistssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserPlaylists objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserPlaylists objects.
     * @throws PropelException
     */
    public function countUserPlaylistss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserPlaylistssPartial && !$this->isNew();
        if (null === $this->collUserPlaylistss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserPlaylistss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserPlaylistss());
            }

            $query = ChildUserPlaylistsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlaylists($this)
                ->count($con);
        }

        return count($this->collUserPlaylistss);
    }

    /**
     * Method called to associate a ChildUserPlaylists object to this object
     * through the ChildUserPlaylists foreign key attribute.
     *
     * @param  ChildUserPlaylists $l ChildUserPlaylists
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function addUserPlaylists(ChildUserPlaylists $l)
    {
        if ($this->collUserPlaylistss === null) {
            $this->initUserPlaylistss();
            $this->collUserPlaylistssPartial = true;
        }

        if (!$this->collUserPlaylistss->contains($l)) {
            $this->doAddUserPlaylists($l);

            if ($this->userPlaylistssScheduledForDeletion and $this->userPlaylistssScheduledForDeletion->contains($l)) {
                $this->userPlaylistssScheduledForDeletion->remove($this->userPlaylistssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserPlaylists $userPlaylists The ChildUserPlaylists object to add.
     */
    protected function doAddUserPlaylists(ChildUserPlaylists $userPlaylists)
    {
        $this->collUserPlaylistss[]= $userPlaylists;
        $userPlaylists->setPlaylists($this);
    }

    /**
     * @param  ChildUserPlaylists $userPlaylists The ChildUserPlaylists object to remove.
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function removeUserPlaylists(ChildUserPlaylists $userPlaylists)
    {
        if ($this->getUserPlaylistss()->contains($userPlaylists)) {
            $pos = $this->collUserPlaylistss->search($userPlaylists);
            $this->collUserPlaylistss->remove($pos);
            if (null === $this->userPlaylistssScheduledForDeletion) {
                $this->userPlaylistssScheduledForDeletion = clone $this->collUserPlaylistss;
                $this->userPlaylistssScheduledForDeletion->clear();
            }
            $this->userPlaylistssScheduledForDeletion[]= clone $userPlaylists;
            $userPlaylists->setPlaylists(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Playlists is new, it will return
     * an empty collection; or if this Playlists has previously
     * been saved, it will retrieve related UserPlaylistss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Playlists.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserPlaylists[] List of ChildUserPlaylists objects
     */
    public function getUserPlaylistssJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserPlaylistsQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getUserPlaylistss($query, $con);
    }

    /**
     * Clears out the collPlaylistChildrensRelatedByParentId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlaylistChildrensRelatedByParentId()
     */
    public function clearPlaylistChildrensRelatedByParentId()
    {
        $this->collPlaylistChildrensRelatedByParentId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlaylistChildrensRelatedByParentId collection loaded partially.
     */
    public function resetPartialPlaylistChildrensRelatedByParentId($v = true)
    {
        $this->collPlaylistChildrensRelatedByParentIdPartial = $v;
    }

    /**
     * Initializes the collPlaylistChildrensRelatedByParentId collection.
     *
     * By default this just sets the collPlaylistChildrensRelatedByParentId collection to an empty array (like clearcollPlaylistChildrensRelatedByParentId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlaylistChildrensRelatedByParentId($overrideExisting = true)
    {
        if (null !== $this->collPlaylistChildrensRelatedByParentId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlaylistChildrenTableMap::getTableMap()->getCollectionClassName();

        $this->collPlaylistChildrensRelatedByParentId = new $collectionClassName;
        $this->collPlaylistChildrensRelatedByParentId->setModel('\Models\Playlists\PlaylistChildren');
    }

    /**
     * Gets an array of ChildPlaylistChildren objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlaylists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlaylistChildren[] List of ChildPlaylistChildren objects
     * @throws PropelException
     */
    public function getPlaylistChildrensRelatedByParentId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistChildrensRelatedByParentIdPartial && !$this->isNew();
        if (null === $this->collPlaylistChildrensRelatedByParentId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlaylistChildrensRelatedByParentId) {
                // return empty collection
                $this->initPlaylistChildrensRelatedByParentId();
            } else {
                $collPlaylistChildrensRelatedByParentId = ChildPlaylistChildrenQuery::create(null, $criteria)
                    ->filterByPlaylistsParent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlaylistChildrensRelatedByParentIdPartial && count($collPlaylistChildrensRelatedByParentId)) {
                        $this->initPlaylistChildrensRelatedByParentId(false);

                        foreach ($collPlaylistChildrensRelatedByParentId as $obj) {
                            if (false == $this->collPlaylistChildrensRelatedByParentId->contains($obj)) {
                                $this->collPlaylistChildrensRelatedByParentId->append($obj);
                            }
                        }

                        $this->collPlaylistChildrensRelatedByParentIdPartial = true;
                    }

                    return $collPlaylistChildrensRelatedByParentId;
                }

                if ($partial && $this->collPlaylistChildrensRelatedByParentId) {
                    foreach ($this->collPlaylistChildrensRelatedByParentId as $obj) {
                        if ($obj->isNew()) {
                            $collPlaylistChildrensRelatedByParentId[] = $obj;
                        }
                    }
                }

                $this->collPlaylistChildrensRelatedByParentId = $collPlaylistChildrensRelatedByParentId;
                $this->collPlaylistChildrensRelatedByParentIdPartial = false;
            }
        }

        return $this->collPlaylistChildrensRelatedByParentId;
    }

    /**
     * Sets a collection of ChildPlaylistChildren objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playlistChildrensRelatedByParentId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function setPlaylistChildrensRelatedByParentId(Collection $playlistChildrensRelatedByParentId, ConnectionInterface $con = null)
    {
        /** @var ChildPlaylistChildren[] $playlistChildrensRelatedByParentIdToDelete */
        $playlistChildrensRelatedByParentIdToDelete = $this->getPlaylistChildrensRelatedByParentId(new Criteria(), $con)->diff($playlistChildrensRelatedByParentId);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playlistChildrensRelatedByParentIdScheduledForDeletion = clone $playlistChildrensRelatedByParentIdToDelete;

        foreach ($playlistChildrensRelatedByParentIdToDelete as $playlistChildrenRelatedByParentIdRemoved) {
            $playlistChildrenRelatedByParentIdRemoved->setPlaylistsParent(null);
        }

        $this->collPlaylistChildrensRelatedByParentId = null;
        foreach ($playlistChildrensRelatedByParentId as $playlistChildrenRelatedByParentId) {
            $this->addPlaylistChildrenRelatedByParentId($playlistChildrenRelatedByParentId);
        }

        $this->collPlaylistChildrensRelatedByParentId = $playlistChildrensRelatedByParentId;
        $this->collPlaylistChildrensRelatedByParentIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlaylistChildren objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlaylistChildren objects.
     * @throws PropelException
     */
    public function countPlaylistChildrensRelatedByParentId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistChildrensRelatedByParentIdPartial && !$this->isNew();
        if (null === $this->collPlaylistChildrensRelatedByParentId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlaylistChildrensRelatedByParentId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlaylistChildrensRelatedByParentId());
            }

            $query = ChildPlaylistChildrenQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlaylistsParent($this)
                ->count($con);
        }

        return count($this->collPlaylistChildrensRelatedByParentId);
    }

    /**
     * Method called to associate a ChildPlaylistChildren object to this object
     * through the ChildPlaylistChildren foreign key attribute.
     *
     * @param  ChildPlaylistChildren $l ChildPlaylistChildren
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function addPlaylistChildrenRelatedByParentId(ChildPlaylistChildren $l)
    {
        if ($this->collPlaylistChildrensRelatedByParentId === null) {
            $this->initPlaylistChildrensRelatedByParentId();
            $this->collPlaylistChildrensRelatedByParentIdPartial = true;
        }

        if (!$this->collPlaylistChildrensRelatedByParentId->contains($l)) {
            $this->doAddPlaylistChildrenRelatedByParentId($l);

            if ($this->playlistChildrensRelatedByParentIdScheduledForDeletion and $this->playlistChildrensRelatedByParentIdScheduledForDeletion->contains($l)) {
                $this->playlistChildrensRelatedByParentIdScheduledForDeletion->remove($this->playlistChildrensRelatedByParentIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlaylistChildren $playlistChildrenRelatedByParentId The ChildPlaylistChildren object to add.
     */
    protected function doAddPlaylistChildrenRelatedByParentId(ChildPlaylistChildren $playlistChildrenRelatedByParentId)
    {
        $this->collPlaylistChildrensRelatedByParentId[]= $playlistChildrenRelatedByParentId;
        $playlistChildrenRelatedByParentId->setPlaylistsParent($this);
    }

    /**
     * @param  ChildPlaylistChildren $playlistChildrenRelatedByParentId The ChildPlaylistChildren object to remove.
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function removePlaylistChildrenRelatedByParentId(ChildPlaylistChildren $playlistChildrenRelatedByParentId)
    {
        if ($this->getPlaylistChildrensRelatedByParentId()->contains($playlistChildrenRelatedByParentId)) {
            $pos = $this->collPlaylistChildrensRelatedByParentId->search($playlistChildrenRelatedByParentId);
            $this->collPlaylistChildrensRelatedByParentId->remove($pos);
            if (null === $this->playlistChildrensRelatedByParentIdScheduledForDeletion) {
                $this->playlistChildrensRelatedByParentIdScheduledForDeletion = clone $this->collPlaylistChildrensRelatedByParentId;
                $this->playlistChildrensRelatedByParentIdScheduledForDeletion->clear();
            }
            $this->playlistChildrensRelatedByParentIdScheduledForDeletion[]= clone $playlistChildrenRelatedByParentId;
            $playlistChildrenRelatedByParentId->setPlaylistsParent(null);
        }

        return $this;
    }

    /**
     * Clears out the collPlaylistChildrensRelatedByChildId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlaylistChildrensRelatedByChildId()
     */
    public function clearPlaylistChildrensRelatedByChildId()
    {
        $this->collPlaylistChildrensRelatedByChildId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlaylistChildrensRelatedByChildId collection loaded partially.
     */
    public function resetPartialPlaylistChildrensRelatedByChildId($v = true)
    {
        $this->collPlaylistChildrensRelatedByChildIdPartial = $v;
    }

    /**
     * Initializes the collPlaylistChildrensRelatedByChildId collection.
     *
     * By default this just sets the collPlaylistChildrensRelatedByChildId collection to an empty array (like clearcollPlaylistChildrensRelatedByChildId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlaylistChildrensRelatedByChildId($overrideExisting = true)
    {
        if (null !== $this->collPlaylistChildrensRelatedByChildId && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlaylistChildrenTableMap::getTableMap()->getCollectionClassName();

        $this->collPlaylistChildrensRelatedByChildId = new $collectionClassName;
        $this->collPlaylistChildrensRelatedByChildId->setModel('\Models\Playlists\PlaylistChildren');
    }

    /**
     * Gets an array of ChildPlaylistChildren objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlaylists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlaylistChildren[] List of ChildPlaylistChildren objects
     * @throws PropelException
     */
    public function getPlaylistChildrensRelatedByChildId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistChildrensRelatedByChildIdPartial && !$this->isNew();
        if (null === $this->collPlaylistChildrensRelatedByChildId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlaylistChildrensRelatedByChildId) {
                // return empty collection
                $this->initPlaylistChildrensRelatedByChildId();
            } else {
                $collPlaylistChildrensRelatedByChildId = ChildPlaylistChildrenQuery::create(null, $criteria)
                    ->filterByPlaylistsChild($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlaylistChildrensRelatedByChildIdPartial && count($collPlaylistChildrensRelatedByChildId)) {
                        $this->initPlaylistChildrensRelatedByChildId(false);

                        foreach ($collPlaylistChildrensRelatedByChildId as $obj) {
                            if (false == $this->collPlaylistChildrensRelatedByChildId->contains($obj)) {
                                $this->collPlaylistChildrensRelatedByChildId->append($obj);
                            }
                        }

                        $this->collPlaylistChildrensRelatedByChildIdPartial = true;
                    }

                    return $collPlaylistChildrensRelatedByChildId;
                }

                if ($partial && $this->collPlaylistChildrensRelatedByChildId) {
                    foreach ($this->collPlaylistChildrensRelatedByChildId as $obj) {
                        if ($obj->isNew()) {
                            $collPlaylistChildrensRelatedByChildId[] = $obj;
                        }
                    }
                }

                $this->collPlaylistChildrensRelatedByChildId = $collPlaylistChildrensRelatedByChildId;
                $this->collPlaylistChildrensRelatedByChildIdPartial = false;
            }
        }

        return $this->collPlaylistChildrensRelatedByChildId;
    }

    /**
     * Sets a collection of ChildPlaylistChildren objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playlistChildrensRelatedByChildId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function setPlaylistChildrensRelatedByChildId(Collection $playlistChildrensRelatedByChildId, ConnectionInterface $con = null)
    {
        /** @var ChildPlaylistChildren[] $playlistChildrensRelatedByChildIdToDelete */
        $playlistChildrensRelatedByChildIdToDelete = $this->getPlaylistChildrensRelatedByChildId(new Criteria(), $con)->diff($playlistChildrensRelatedByChildId);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playlistChildrensRelatedByChildIdScheduledForDeletion = clone $playlistChildrensRelatedByChildIdToDelete;

        foreach ($playlistChildrensRelatedByChildIdToDelete as $playlistChildrenRelatedByChildIdRemoved) {
            $playlistChildrenRelatedByChildIdRemoved->setPlaylistsChild(null);
        }

        $this->collPlaylistChildrensRelatedByChildId = null;
        foreach ($playlistChildrensRelatedByChildId as $playlistChildrenRelatedByChildId) {
            $this->addPlaylistChildrenRelatedByChildId($playlistChildrenRelatedByChildId);
        }

        $this->collPlaylistChildrensRelatedByChildId = $playlistChildrensRelatedByChildId;
        $this->collPlaylistChildrensRelatedByChildIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlaylistChildren objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlaylistChildren objects.
     * @throws PropelException
     */
    public function countPlaylistChildrensRelatedByChildId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistChildrensRelatedByChildIdPartial && !$this->isNew();
        if (null === $this->collPlaylistChildrensRelatedByChildId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlaylistChildrensRelatedByChildId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlaylistChildrensRelatedByChildId());
            }

            $query = ChildPlaylistChildrenQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlaylistsChild($this)
                ->count($con);
        }

        return count($this->collPlaylistChildrensRelatedByChildId);
    }

    /**
     * Method called to associate a ChildPlaylistChildren object to this object
     * through the ChildPlaylistChildren foreign key attribute.
     *
     * @param  ChildPlaylistChildren $l ChildPlaylistChildren
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function addPlaylistChildrenRelatedByChildId(ChildPlaylistChildren $l)
    {
        if ($this->collPlaylistChildrensRelatedByChildId === null) {
            $this->initPlaylistChildrensRelatedByChildId();
            $this->collPlaylistChildrensRelatedByChildIdPartial = true;
        }

        if (!$this->collPlaylistChildrensRelatedByChildId->contains($l)) {
            $this->doAddPlaylistChildrenRelatedByChildId($l);

            if ($this->playlistChildrensRelatedByChildIdScheduledForDeletion and $this->playlistChildrensRelatedByChildIdScheduledForDeletion->contains($l)) {
                $this->playlistChildrensRelatedByChildIdScheduledForDeletion->remove($this->playlistChildrensRelatedByChildIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlaylistChildren $playlistChildrenRelatedByChildId The ChildPlaylistChildren object to add.
     */
    protected function doAddPlaylistChildrenRelatedByChildId(ChildPlaylistChildren $playlistChildrenRelatedByChildId)
    {
        $this->collPlaylistChildrensRelatedByChildId[]= $playlistChildrenRelatedByChildId;
        $playlistChildrenRelatedByChildId->setPlaylistsChild($this);
    }

    /**
     * @param  ChildPlaylistChildren $playlistChildrenRelatedByChildId The ChildPlaylistChildren object to remove.
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function removePlaylistChildrenRelatedByChildId(ChildPlaylistChildren $playlistChildrenRelatedByChildId)
    {
        if ($this->getPlaylistChildrensRelatedByChildId()->contains($playlistChildrenRelatedByChildId)) {
            $pos = $this->collPlaylistChildrensRelatedByChildId->search($playlistChildrenRelatedByChildId);
            $this->collPlaylistChildrensRelatedByChildId->remove($pos);
            if (null === $this->playlistChildrensRelatedByChildIdScheduledForDeletion) {
                $this->playlistChildrensRelatedByChildIdScheduledForDeletion = clone $this->collPlaylistChildrensRelatedByChildId;
                $this->playlistChildrensRelatedByChildIdScheduledForDeletion->clear();
            }
            $this->playlistChildrensRelatedByChildIdScheduledForDeletion[]= clone $playlistChildrenRelatedByChildId;
            $playlistChildrenRelatedByChildId->setPlaylistsChild(null);
        }

        return $this;
    }

    /**
     * Clears out the collPlaylistCommentss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlaylistCommentss()
     */
    public function clearPlaylistCommentss()
    {
        $this->collPlaylistCommentss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlaylistCommentss collection loaded partially.
     */
    public function resetPartialPlaylistCommentss($v = true)
    {
        $this->collPlaylistCommentssPartial = $v;
    }

    /**
     * Initializes the collPlaylistCommentss collection.
     *
     * By default this just sets the collPlaylistCommentss collection to an empty array (like clearcollPlaylistCommentss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlaylistCommentss($overrideExisting = true)
    {
        if (null !== $this->collPlaylistCommentss && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlaylistCommentsTableMap::getTableMap()->getCollectionClassName();

        $this->collPlaylistCommentss = new $collectionClassName;
        $this->collPlaylistCommentss->setModel('\Models\Playlists\PlaylistComments');
    }

    /**
     * Gets an array of ChildPlaylistComments objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlaylists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlaylistComments[] List of ChildPlaylistComments objects
     * @throws PropelException
     */
    public function getPlaylistCommentss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistCommentssPartial && !$this->isNew();
        if (null === $this->collPlaylistCommentss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlaylistCommentss) {
                // return empty collection
                $this->initPlaylistCommentss();
            } else {
                $collPlaylistCommentss = ChildPlaylistCommentsQuery::create(null, $criteria)
                    ->filterByPlaylists($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlaylistCommentssPartial && count($collPlaylistCommentss)) {
                        $this->initPlaylistCommentss(false);

                        foreach ($collPlaylistCommentss as $obj) {
                            if (false == $this->collPlaylistCommentss->contains($obj)) {
                                $this->collPlaylistCommentss->append($obj);
                            }
                        }

                        $this->collPlaylistCommentssPartial = true;
                    }

                    return $collPlaylistCommentss;
                }

                if ($partial && $this->collPlaylistCommentss) {
                    foreach ($this->collPlaylistCommentss as $obj) {
                        if ($obj->isNew()) {
                            $collPlaylistCommentss[] = $obj;
                        }
                    }
                }

                $this->collPlaylistCommentss = $collPlaylistCommentss;
                $this->collPlaylistCommentssPartial = false;
            }
        }

        return $this->collPlaylistCommentss;
    }

    /**
     * Sets a collection of ChildPlaylistComments objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playlistCommentss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function setPlaylistCommentss(Collection $playlistCommentss, ConnectionInterface $con = null)
    {
        /** @var ChildPlaylistComments[] $playlistCommentssToDelete */
        $playlistCommentssToDelete = $this->getPlaylistCommentss(new Criteria(), $con)->diff($playlistCommentss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playlistCommentssScheduledForDeletion = clone $playlistCommentssToDelete;

        foreach ($playlistCommentssToDelete as $playlistCommentsRemoved) {
            $playlistCommentsRemoved->setPlaylists(null);
        }

        $this->collPlaylistCommentss = null;
        foreach ($playlistCommentss as $playlistComments) {
            $this->addPlaylistComments($playlistComments);
        }

        $this->collPlaylistCommentss = $playlistCommentss;
        $this->collPlaylistCommentssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PlaylistComments objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlaylistComments objects.
     * @throws PropelException
     */
    public function countPlaylistCommentss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistCommentssPartial && !$this->isNew();
        if (null === $this->collPlaylistCommentss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlaylistCommentss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlaylistCommentss());
            }

            $query = ChildPlaylistCommentsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlaylists($this)
                ->count($con);
        }

        return count($this->collPlaylistCommentss);
    }

    /**
     * Method called to associate a ChildPlaylistComments object to this object
     * through the ChildPlaylistComments foreign key attribute.
     *
     * @param  ChildPlaylistComments $l ChildPlaylistComments
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function addPlaylistComments(ChildPlaylistComments $l)
    {
        if ($this->collPlaylistCommentss === null) {
            $this->initPlaylistCommentss();
            $this->collPlaylistCommentssPartial = true;
        }

        if (!$this->collPlaylistCommentss->contains($l)) {
            $this->doAddPlaylistComments($l);

            if ($this->playlistCommentssScheduledForDeletion and $this->playlistCommentssScheduledForDeletion->contains($l)) {
                $this->playlistCommentssScheduledForDeletion->remove($this->playlistCommentssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPlaylistComments $playlistComments The ChildPlaylistComments object to add.
     */
    protected function doAddPlaylistComments(ChildPlaylistComments $playlistComments)
    {
        $this->collPlaylistCommentss[]= $playlistComments;
        $playlistComments->setPlaylists($this);
    }

    /**
     * @param  ChildPlaylistComments $playlistComments The ChildPlaylistComments object to remove.
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function removePlaylistComments(ChildPlaylistComments $playlistComments)
    {
        if ($this->getPlaylistCommentss()->contains($playlistComments)) {
            $pos = $this->collPlaylistCommentss->search($playlistComments);
            $this->collPlaylistCommentss->remove($pos);
            if (null === $this->playlistCommentssScheduledForDeletion) {
                $this->playlistCommentssScheduledForDeletion = clone $this->collPlaylistCommentss;
                $this->playlistCommentssScheduledForDeletion->clear();
            }
            $this->playlistCommentssScheduledForDeletion[]= clone $playlistComments;
            $playlistComments->setPlaylists(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Playlists is new, it will return
     * an empty collection; or if this Playlists has previously
     * been saved, it will retrieve related PlaylistCommentss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Playlists.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlaylistComments[] List of ChildPlaylistComments objects
     */
    public function getPlaylistCommentssJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlaylistCommentsQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getPlaylistCommentss($query, $con);
    }

    /**
     * Clears out the collPlaylistEpisodess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlaylistEpisodess()
     */
    public function clearPlaylistEpisodess()
    {
        $this->collPlaylistEpisodess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPlaylistEpisodess collection loaded partially.
     */
    public function resetPartialPlaylistEpisodess($v = true)
    {
        $this->collPlaylistEpisodessPartial = $v;
    }

    /**
     * Initializes the collPlaylistEpisodess collection.
     *
     * By default this just sets the collPlaylistEpisodess collection to an empty array (like clearcollPlaylistEpisodess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPlaylistEpisodess($overrideExisting = true)
    {
        if (null !== $this->collPlaylistEpisodess && !$overrideExisting) {
            return;
        }

        $collectionClassName = PlaylistEpisodesTableMap::getTableMap()->getCollectionClassName();

        $this->collPlaylistEpisodess = new $collectionClassName;
        $this->collPlaylistEpisodess->setModel('\Models\Episodes\PlaylistEpisodes');
    }

    /**
     * Gets an array of PlaylistEpisodes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlaylists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|PlaylistEpisodes[] List of PlaylistEpisodes objects
     * @throws PropelException
     */
    public function getPlaylistEpisodess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistEpisodessPartial && !$this->isNew();
        if (null === $this->collPlaylistEpisodess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPlaylistEpisodess) {
                // return empty collection
                $this->initPlaylistEpisodess();
            } else {
                $collPlaylistEpisodess = PlaylistEpisodesQuery::create(null, $criteria)
                    ->filterByPlaylist($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPlaylistEpisodessPartial && count($collPlaylistEpisodess)) {
                        $this->initPlaylistEpisodess(false);

                        foreach ($collPlaylistEpisodess as $obj) {
                            if (false == $this->collPlaylistEpisodess->contains($obj)) {
                                $this->collPlaylistEpisodess->append($obj);
                            }
                        }

                        $this->collPlaylistEpisodessPartial = true;
                    }

                    return $collPlaylistEpisodess;
                }

                if ($partial && $this->collPlaylistEpisodess) {
                    foreach ($this->collPlaylistEpisodess as $obj) {
                        if ($obj->isNew()) {
                            $collPlaylistEpisodess[] = $obj;
                        }
                    }
                }

                $this->collPlaylistEpisodess = $collPlaylistEpisodess;
                $this->collPlaylistEpisodessPartial = false;
            }
        }

        return $this->collPlaylistEpisodess;
    }

    /**
     * Sets a collection of PlaylistEpisodes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playlistEpisodess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function setPlaylistEpisodess(Collection $playlistEpisodess, ConnectionInterface $con = null)
    {
        /** @var PlaylistEpisodes[] $playlistEpisodessToDelete */
        $playlistEpisodessToDelete = $this->getPlaylistEpisodess(new Criteria(), $con)->diff($playlistEpisodess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playlistEpisodessScheduledForDeletion = clone $playlistEpisodessToDelete;

        foreach ($playlistEpisodessToDelete as $playlistEpisodesRemoved) {
            $playlistEpisodesRemoved->setPlaylist(null);
        }

        $this->collPlaylistEpisodess = null;
        foreach ($playlistEpisodess as $playlistEpisodes) {
            $this->addPlaylistEpisodes($playlistEpisodes);
        }

        $this->collPlaylistEpisodess = $playlistEpisodess;
        $this->collPlaylistEpisodessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BasePlaylistEpisodes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BasePlaylistEpisodes objects.
     * @throws PropelException
     */
    public function countPlaylistEpisodess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistEpisodessPartial && !$this->isNew();
        if (null === $this->collPlaylistEpisodess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlaylistEpisodess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPlaylistEpisodess());
            }

            $query = PlaylistEpisodesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlaylist($this)
                ->count($con);
        }

        return count($this->collPlaylistEpisodess);
    }

    /**
     * Method called to associate a PlaylistEpisodes object to this object
     * through the PlaylistEpisodes foreign key attribute.
     *
     * @param  PlaylistEpisodes $l PlaylistEpisodes
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function addPlaylistEpisodes(PlaylistEpisodes $l)
    {
        if ($this->collPlaylistEpisodess === null) {
            $this->initPlaylistEpisodess();
            $this->collPlaylistEpisodessPartial = true;
        }

        if (!$this->collPlaylistEpisodess->contains($l)) {
            $this->doAddPlaylistEpisodes($l);

            if ($this->playlistEpisodessScheduledForDeletion and $this->playlistEpisodessScheduledForDeletion->contains($l)) {
                $this->playlistEpisodessScheduledForDeletion->remove($this->playlistEpisodessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param PlaylistEpisodes $playlistEpisodes The PlaylistEpisodes object to add.
     */
    protected function doAddPlaylistEpisodes(PlaylistEpisodes $playlistEpisodes)
    {
        $this->collPlaylistEpisodess[]= $playlistEpisodes;
        $playlistEpisodes->setPlaylist($this);
    }

    /**
     * @param  PlaylistEpisodes $playlistEpisodes The PlaylistEpisodes object to remove.
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function removePlaylistEpisodes(PlaylistEpisodes $playlistEpisodes)
    {
        if ($this->getPlaylistEpisodess()->contains($playlistEpisodes)) {
            $pos = $this->collPlaylistEpisodess->search($playlistEpisodes);
            $this->collPlaylistEpisodess->remove($pos);
            if (null === $this->playlistEpisodessScheduledForDeletion) {
                $this->playlistEpisodessScheduledForDeletion = clone $this->collPlaylistEpisodess;
                $this->playlistEpisodessScheduledForDeletion->clear();
            }
            $this->playlistEpisodessScheduledForDeletion[]= clone $playlistEpisodes;
            $playlistEpisodes->setPlaylist(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Playlists is new, it will return
     * an empty collection; or if this Playlists has previously
     * been saved, it will retrieve related PlaylistEpisodess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Playlists.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|PlaylistEpisodes[] List of PlaylistEpisodes objects
     */
    public function getPlaylistEpisodessJoinEpisode(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = PlaylistEpisodesQuery::create(null, $criteria);
        $query->joinWith('Episode', $joinBehavior);

        return $this->getPlaylistEpisodess($query, $con);
    }

    /**
     * Clears out the collUserPlaylistTagss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserPlaylistTagss()
     */
    public function clearUserPlaylistTagss()
    {
        $this->collUserPlaylistTagss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserPlaylistTagss collection loaded partially.
     */
    public function resetPartialUserPlaylistTagss($v = true)
    {
        $this->collUserPlaylistTagssPartial = $v;
    }

    /**
     * Initializes the collUserPlaylistTagss collection.
     *
     * By default this just sets the collUserPlaylistTagss collection to an empty array (like clearcollUserPlaylistTagss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserPlaylistTagss($overrideExisting = true)
    {
        if (null !== $this->collUserPlaylistTagss && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserPlaylistTagsTableMap::getTableMap()->getCollectionClassName();

        $this->collUserPlaylistTagss = new $collectionClassName;
        $this->collUserPlaylistTagss->setModel('\Models\UserTags\UserPlaylistTags');
    }

    /**
     * Gets an array of UserPlaylistTags objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlaylists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|UserPlaylistTags[] List of UserPlaylistTags objects
     * @throws PropelException
     */
    public function getUserPlaylistTagss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserPlaylistTagssPartial && !$this->isNew();
        if (null === $this->collUserPlaylistTagss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserPlaylistTagss) {
                // return empty collection
                $this->initUserPlaylistTagss();
            } else {
                $collUserPlaylistTagss = UserPlaylistTagsQuery::create(null, $criteria)
                    ->filterByPlaylistsTags($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserPlaylistTagssPartial && count($collUserPlaylistTagss)) {
                        $this->initUserPlaylistTagss(false);

                        foreach ($collUserPlaylistTagss as $obj) {
                            if (false == $this->collUserPlaylistTagss->contains($obj)) {
                                $this->collUserPlaylistTagss->append($obj);
                            }
                        }

                        $this->collUserPlaylistTagssPartial = true;
                    }

                    return $collUserPlaylistTagss;
                }

                if ($partial && $this->collUserPlaylistTagss) {
                    foreach ($this->collUserPlaylistTagss as $obj) {
                        if ($obj->isNew()) {
                            $collUserPlaylistTagss[] = $obj;
                        }
                    }
                }

                $this->collUserPlaylistTagss = $collUserPlaylistTagss;
                $this->collUserPlaylistTagssPartial = false;
            }
        }

        return $this->collUserPlaylistTagss;
    }

    /**
     * Sets a collection of UserPlaylistTags objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userPlaylistTagss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function setUserPlaylistTagss(Collection $userPlaylistTagss, ConnectionInterface $con = null)
    {
        /** @var UserPlaylistTags[] $userPlaylistTagssToDelete */
        $userPlaylistTagssToDelete = $this->getUserPlaylistTagss(new Criteria(), $con)->diff($userPlaylistTagss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userPlaylistTagssScheduledForDeletion = clone $userPlaylistTagssToDelete;

        foreach ($userPlaylistTagssToDelete as $userPlaylistTagsRemoved) {
            $userPlaylistTagsRemoved->setPlaylistsTags(null);
        }

        $this->collUserPlaylistTagss = null;
        foreach ($userPlaylistTagss as $userPlaylistTags) {
            $this->addUserPlaylistTags($userPlaylistTags);
        }

        $this->collUserPlaylistTagss = $userPlaylistTagss;
        $this->collUserPlaylistTagssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseUserPlaylistTags objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseUserPlaylistTags objects.
     * @throws PropelException
     */
    public function countUserPlaylistTagss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserPlaylistTagssPartial && !$this->isNew();
        if (null === $this->collUserPlaylistTagss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserPlaylistTagss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserPlaylistTagss());
            }

            $query = UserPlaylistTagsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlaylistsTags($this)
                ->count($con);
        }

        return count($this->collUserPlaylistTagss);
    }

    /**
     * Method called to associate a UserPlaylistTags object to this object
     * through the UserPlaylistTags foreign key attribute.
     *
     * @param  UserPlaylistTags $l UserPlaylistTags
     * @return $this|\Models\Playlists\Playlists The current object (for fluent API support)
     */
    public function addUserPlaylistTags(UserPlaylistTags $l)
    {
        if ($this->collUserPlaylistTagss === null) {
            $this->initUserPlaylistTagss();
            $this->collUserPlaylistTagssPartial = true;
        }

        if (!$this->collUserPlaylistTagss->contains($l)) {
            $this->doAddUserPlaylistTags($l);

            if ($this->userPlaylistTagssScheduledForDeletion and $this->userPlaylistTagssScheduledForDeletion->contains($l)) {
                $this->userPlaylistTagssScheduledForDeletion->remove($this->userPlaylistTagssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param UserPlaylistTags $userPlaylistTags The UserPlaylistTags object to add.
     */
    protected function doAddUserPlaylistTags(UserPlaylistTags $userPlaylistTags)
    {
        $this->collUserPlaylistTagss[]= $userPlaylistTags;
        $userPlaylistTags->setPlaylistsTags($this);
    }

    /**
     * @param  UserPlaylistTags $userPlaylistTags The UserPlaylistTags object to remove.
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function removeUserPlaylistTags(UserPlaylistTags $userPlaylistTags)
    {
        if ($this->getUserPlaylistTagss()->contains($userPlaylistTags)) {
            $pos = $this->collUserPlaylistTagss->search($userPlaylistTags);
            $this->collUserPlaylistTagss->remove($pos);
            if (null === $this->userPlaylistTagssScheduledForDeletion) {
                $this->userPlaylistTagssScheduledForDeletion = clone $this->collUserPlaylistTagss;
                $this->userPlaylistTagssScheduledForDeletion->clear();
            }
            $this->userPlaylistTagssScheduledForDeletion[]= clone $userPlaylistTags;
            $userPlaylistTags->setPlaylistsTags(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Playlists is new, it will return
     * an empty collection; or if this Playlists has previously
     * been saved, it will retrieve related UserPlaylistTagss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Playlists.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|UserPlaylistTags[] List of UserPlaylistTags objects
     */
    public function getUserPlaylistTagssJoinPlaylistTag(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = UserPlaylistTagsQuery::create(null, $criteria);
        $query->joinWith('PlaylistTag', $joinBehavior);

        return $this->getUserPlaylistTagss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Playlists is new, it will return
     * an empty collection; or if this Playlists has previously
     * been saved, it will retrieve related UserPlaylistTagss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Playlists.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|UserPlaylistTags[] List of UserPlaylistTags objects
     */
    public function getUserPlaylistTagssJoinUsersPlaylistTags(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = UserPlaylistTagsQuery::create(null, $criteria);
        $query->joinWith('UsersPlaylistTags', $joinBehavior);

        return $this->getUserPlaylistTagss($query, $con);
    }

    /**
     * Clears out the collUserss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserss()
     */
    public function clearUserss()
    {
        $this->collUserss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collUserss crossRef collection.
     *
     * By default this just sets the collUserss collection to an empty collection (like clearUserss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initUserss()
    {
        $collectionClassName = UserPlaylistsTableMap::getTableMap()->getCollectionClassName();

        $this->collUserss = new $collectionClassName;
        $this->collUserssPartial = true;
        $this->collUserss->setModel('\Models\Users\Users');
    }

    /**
     * Checks if the collUserss collection is loaded.
     *
     * @return bool
     */
    public function isUserssLoaded()
    {
        return null !== $this->collUserss;
    }

    /**
     * Gets a collection of Users objects related by a many-to-many relationship
     * to the current object by way of the user_playlists cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlaylists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|Users[] List of Users objects
     */
    public function getUserss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserssPartial && !$this->isNew();
        if (null === $this->collUserss || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collUserss) {
                    $this->initUserss();
                }
            } else {

                $query = UsersQuery::create(null, $criteria)
                    ->filterByPlaylists($this);
                $collUserss = $query->find($con);
                if (null !== $criteria) {
                    return $collUserss;
                }

                if ($partial && $this->collUserss) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collUserss as $obj) {
                        if (!$collUserss->contains($obj)) {
                            $collUserss[] = $obj;
                        }
                    }
                }

                $this->collUserss = $collUserss;
                $this->collUserssPartial = false;
            }
        }

        return $this->collUserss;
    }

    /**
     * Sets a collection of Users objects related by a many-to-many relationship
     * to the current object by way of the user_playlists cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $userss A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function setUserss(Collection $userss, ConnectionInterface $con = null)
    {
        $this->clearUserss();
        $currentUserss = $this->getUserss();

        $userssScheduledForDeletion = $currentUserss->diff($userss);

        foreach ($userssScheduledForDeletion as $toDelete) {
            $this->removeUsers($toDelete);
        }

        foreach ($userss as $users) {
            if (!$currentUserss->contains($users)) {
                $this->doAddUsers($users);
            }
        }

        $this->collUserssPartial = false;
        $this->collUserss = $userss;

        return $this;
    }

    /**
     * Gets the number of Users objects related by a many-to-many relationship
     * to the current object by way of the user_playlists cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Users objects
     */
    public function countUserss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserssPartial && !$this->isNew();
        if (null === $this->collUserss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserss) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getUserss());
                }

                $query = UsersQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPlaylists($this)
                    ->count($con);
            }
        } else {
            return count($this->collUserss);
        }
    }

    /**
     * Associate a Users to this object
     * through the user_playlists cross reference table.
     *
     * @param Users $users
     * @return ChildPlaylists The current object (for fluent API support)
     */
    public function addUsers(Users $users)
    {
        if ($this->collUserss === null) {
            $this->initUserss();
        }

        if (!$this->getUserss()->contains($users)) {
            // only add it if the **same** object is not already associated
            $this->collUserss->push($users);
            $this->doAddUsers($users);
        }

        return $this;
    }

    /**
     *
     * @param Users $users
     */
    protected function doAddUsers(Users $users)
    {
        $userPlaylists = new ChildUserPlaylists();

        $userPlaylists->setUsers($users);

        $userPlaylists->setPlaylists($this);

        $this->addUserPlaylists($userPlaylists);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$users->isPlaylistssLoaded()) {
            $users->initPlaylistss();
            $users->getPlaylistss()->push($this);
        } elseif (!$users->getPlaylistss()->contains($this)) {
            $users->getPlaylistss()->push($this);
        }

    }

    /**
     * Remove users of this object
     * through the user_playlists cross reference table.
     *
     * @param Users $users
     * @return ChildPlaylists The current object (for fluent API support)
     */
    public function removeUsers(Users $users)
    {
        if ($this->getUserss()->contains($users)) {
            $userPlaylists = new ChildUserPlaylists();
            $userPlaylists->setUsers($users);
            if ($users->isPlaylistssLoaded()) {
                //remove the back reference if available
                $users->getPlaylistss()->removeObject($this);
            }

            $userPlaylists->setPlaylists($this);
            $this->removeUserPlaylists(clone $userPlaylists);
            $userPlaylists->clear();

            $this->collUserss->remove($this->collUserss->search($users));

            if (null === $this->userssScheduledForDeletion) {
                $this->userssScheduledForDeletion = clone $this->collUserss;
                $this->userssScheduledForDeletion->clear();
            }

            $this->userssScheduledForDeletion->push($users);
        }


        return $this;
    }

    /**
     * Clears out the collPlaylistschildren collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlaylistschildren()
     */
    public function clearPlaylistschildren()
    {
        $this->collPlaylistschildren = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collPlaylistschildren crossRef collection.
     *
     * By default this just sets the collPlaylistschildren collection to an empty collection (like clearPlaylistschildren());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPlaylistschildren()
    {
        $collectionClassName = PlaylistChildrenTableMap::getTableMap()->getCollectionClassName();

        $this->collPlaylistschildren = new $collectionClassName;
        $this->collPlaylistschildrenPartial = true;
        $this->collPlaylistschildren->setModel('\Models\Playlists\Playlists');
    }

    /**
     * Checks if the collPlaylistschildren collection is loaded.
     *
     * @return bool
     */
    public function isPlaylistschildrenLoaded()
    {
        return null !== $this->collPlaylistschildren;
    }

    /**
     * Gets a collection of ChildPlaylists objects related by a many-to-many relationship
     * to the current object by way of the playlists_children cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlaylists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildPlaylists[] List of ChildPlaylists objects
     */
    public function getPlaylistschildren(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistschildrenPartial && !$this->isNew();
        if (null === $this->collPlaylistschildren || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPlaylistschildren) {
                    $this->initPlaylistschildren();
                }
            } else {

                $query = ChildPlaylistsQuery::create(null, $criteria)
                    ->filterByPlaylistsParent($this);
                $collPlaylistschildren = $query->find($con);
                if (null !== $criteria) {
                    return $collPlaylistschildren;
                }

                if ($partial && $this->collPlaylistschildren) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collPlaylistschildren as $obj) {
                        if (!$collPlaylistschildren->contains($obj)) {
                            $collPlaylistschildren[] = $obj;
                        }
                    }
                }

                $this->collPlaylistschildren = $collPlaylistschildren;
                $this->collPlaylistschildrenPartial = false;
            }
        }

        return $this->collPlaylistschildren;
    }

    /**
     * Sets a collection of Playlists objects related by a many-to-many relationship
     * to the current object by way of the playlists_children cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $playlistschildren A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function setPlaylistschildren(Collection $playlistschildren, ConnectionInterface $con = null)
    {
        $this->clearPlaylistschildren();
        $currentPlaylistschildren = $this->getPlaylistschildren();

        $playlistschildrenScheduledForDeletion = $currentPlaylistschildren->diff($playlistschildren);

        foreach ($playlistschildrenScheduledForDeletion as $toDelete) {
            $this->removePlaylistsChild($toDelete);
        }

        foreach ($playlistschildren as $playlistsChild) {
            if (!$currentPlaylistschildren->contains($playlistsChild)) {
                $this->doAddPlaylistsChild($playlistsChild);
            }
        }

        $this->collPlaylistschildrenPartial = false;
        $this->collPlaylistschildren = $playlistschildren;

        return $this;
    }

    /**
     * Gets the number of Playlists objects related by a many-to-many relationship
     * to the current object by way of the playlists_children cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Playlists objects
     */
    public function countPlaylistschildren(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistschildrenPartial && !$this->isNew();
        if (null === $this->collPlaylistschildren || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlaylistschildren) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getPlaylistschildren());
                }

                $query = ChildPlaylistsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPlaylistsParent($this)
                    ->count($con);
            }
        } else {
            return count($this->collPlaylistschildren);
        }
    }

    /**
     * Associate a ChildPlaylists to this object
     * through the playlists_children cross reference table.
     *
     * @param ChildPlaylists $playlistsChild
     * @return ChildPlaylists The current object (for fluent API support)
     */
    public function addPlaylistsChild(ChildPlaylists $playlistsChild)
    {
        if ($this->collPlaylistschildren === null) {
            $this->initPlaylistschildren();
        }

        if (!$this->getPlaylistschildren()->contains($playlistsChild)) {
            // only add it if the **same** object is not already associated
            $this->collPlaylistschildren->push($playlistsChild);
            $this->doAddPlaylistsChild($playlistsChild);
        }

        return $this;
    }

    /**
     *
     * @param ChildPlaylists $playlistsChild
     */
    protected function doAddPlaylistsChild(ChildPlaylists $playlistsChild)
    {
        $playlistChildren = new ChildPlaylistChildren();

        $playlistChildren->setPlaylistsChild($playlistsChild);

        $playlistChildren->setPlaylistsParent($this);

        $this->addPlaylistChildrenRelatedByParentId($playlistChildren);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$playlistsChild->isPlaylistsParentsLoaded()) {
            $playlistsChild->initPlaylistsParents();
            $playlistsChild->getPlaylistsParents()->push($this);
        } elseif (!$playlistsChild->getPlaylistsParents()->contains($this)) {
            $playlistsChild->getPlaylistsParents()->push($this);
        }

    }

    /**
     * Remove playlistsChild of this object
     * through the playlists_children cross reference table.
     *
     * @param ChildPlaylists $playlistsChild
     * @return ChildPlaylists The current object (for fluent API support)
     */
    public function removePlaylistsChild(ChildPlaylists $playlistsChild)
    {
        if ($this->getPlaylistschildren()->contains($playlistsChild)) {
            $playlistChildren = new ChildPlaylistChildren();
            $playlistChildren->setPlaylistsChild($playlistsChild);
            if ($playlistsChild->isPlaylistsParentsLoaded()) {
                //remove the back reference if available
                $playlistsChild->getPlaylistsParents()->removeObject($this);
            }

            $playlistChildren->setPlaylistsParent($this);
            $this->removePlaylistChildrenRelatedByParentId(clone $playlistChildren);
            $playlistChildren->clear();

            $this->collPlaylistschildren->remove($this->collPlaylistschildren->search($playlistsChild));

            if (null === $this->playlistschildrenScheduledForDeletion) {
                $this->playlistschildrenScheduledForDeletion = clone $this->collPlaylistschildren;
                $this->playlistschildrenScheduledForDeletion->clear();
            }

            $this->playlistschildrenScheduledForDeletion->push($playlistsChild);
        }


        return $this;
    }

    /**
     * Clears out the collPlaylistsParents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlaylistsParents()
     */
    public function clearPlaylistsParents()
    {
        $this->collPlaylistsParents = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collPlaylistsParents crossRef collection.
     *
     * By default this just sets the collPlaylistsParents collection to an empty collection (like clearPlaylistsParents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPlaylistsParents()
    {
        $collectionClassName = PlaylistChildrenTableMap::getTableMap()->getCollectionClassName();

        $this->collPlaylistsParents = new $collectionClassName;
        $this->collPlaylistsParentsPartial = true;
        $this->collPlaylistsParents->setModel('\Models\Playlists\Playlists');
    }

    /**
     * Checks if the collPlaylistsParents collection is loaded.
     *
     * @return bool
     */
    public function isPlaylistsParentsLoaded()
    {
        return null !== $this->collPlaylistsParents;
    }

    /**
     * Gets a collection of ChildPlaylists objects related by a many-to-many relationship
     * to the current object by way of the playlists_children cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlaylists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildPlaylists[] List of ChildPlaylists objects
     */
    public function getPlaylistsParents(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistsParentsPartial && !$this->isNew();
        if (null === $this->collPlaylistsParents || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPlaylistsParents) {
                    $this->initPlaylistsParents();
                }
            } else {

                $query = ChildPlaylistsQuery::create(null, $criteria)
                    ->filterByPlaylistsChild($this);
                $collPlaylistsParents = $query->find($con);
                if (null !== $criteria) {
                    return $collPlaylistsParents;
                }

                if ($partial && $this->collPlaylistsParents) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collPlaylistsParents as $obj) {
                        if (!$collPlaylistsParents->contains($obj)) {
                            $collPlaylistsParents[] = $obj;
                        }
                    }
                }

                $this->collPlaylistsParents = $collPlaylistsParents;
                $this->collPlaylistsParentsPartial = false;
            }
        }

        return $this->collPlaylistsParents;
    }

    /**
     * Sets a collection of Playlists objects related by a many-to-many relationship
     * to the current object by way of the playlists_children cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $playlistsParents A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function setPlaylistsParents(Collection $playlistsParents, ConnectionInterface $con = null)
    {
        $this->clearPlaylistsParents();
        $currentPlaylistsParents = $this->getPlaylistsParents();

        $playlistsParentsScheduledForDeletion = $currentPlaylistsParents->diff($playlistsParents);

        foreach ($playlistsParentsScheduledForDeletion as $toDelete) {
            $this->removePlaylistsParent($toDelete);
        }

        foreach ($playlistsParents as $playlistsParent) {
            if (!$currentPlaylistsParents->contains($playlistsParent)) {
                $this->doAddPlaylistsParent($playlistsParent);
            }
        }

        $this->collPlaylistsParentsPartial = false;
        $this->collPlaylistsParents = $playlistsParents;

        return $this;
    }

    /**
     * Gets the number of Playlists objects related by a many-to-many relationship
     * to the current object by way of the playlists_children cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Playlists objects
     */
    public function countPlaylistsParents(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistsParentsPartial && !$this->isNew();
        if (null === $this->collPlaylistsParents || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlaylistsParents) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getPlaylistsParents());
                }

                $query = ChildPlaylistsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPlaylistsChild($this)
                    ->count($con);
            }
        } else {
            return count($this->collPlaylistsParents);
        }
    }

    /**
     * Associate a ChildPlaylists to this object
     * through the playlists_children cross reference table.
     *
     * @param ChildPlaylists $playlistsParent
     * @return ChildPlaylists The current object (for fluent API support)
     */
    public function addPlaylistsParent(ChildPlaylists $playlistsParent)
    {
        if ($this->collPlaylistsParents === null) {
            $this->initPlaylistsParents();
        }

        if (!$this->getPlaylistsParents()->contains($playlistsParent)) {
            // only add it if the **same** object is not already associated
            $this->collPlaylistsParents->push($playlistsParent);
            $this->doAddPlaylistsParent($playlistsParent);
        }

        return $this;
    }

    /**
     *
     * @param ChildPlaylists $playlistsParent
     */
    protected function doAddPlaylistsParent(ChildPlaylists $playlistsParent)
    {
        $playlistChildren = new ChildPlaylistChildren();

        $playlistChildren->setPlaylistsParent($playlistsParent);

        $playlistChildren->setPlaylistsChild($this);

        $this->addPlaylistChildrenRelatedByChildId($playlistChildren);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$playlistsParent->isPlaylistschildrenLoaded()) {
            $playlistsParent->initPlaylistschildren();
            $playlistsParent->getPlaylistschildren()->push($this);
        } elseif (!$playlistsParent->getPlaylistschildren()->contains($this)) {
            $playlistsParent->getPlaylistschildren()->push($this);
        }

    }

    /**
     * Remove playlistsParent of this object
     * through the playlists_children cross reference table.
     *
     * @param ChildPlaylists $playlistsParent
     * @return ChildPlaylists The current object (for fluent API support)
     */
    public function removePlaylistsParent(ChildPlaylists $playlistsParent)
    {
        if ($this->getPlaylistsParents()->contains($playlistsParent)) {
            $playlistChildren = new ChildPlaylistChildren();
            $playlistChildren->setPlaylistsParent($playlistsParent);
            if ($playlistsParent->isPlaylistschildrenLoaded()) {
                //remove the back reference if available
                $playlistsParent->getPlaylistschildren()->removeObject($this);
            }

            $playlistChildren->setPlaylistsChild($this);
            $this->removePlaylistChildrenRelatedByChildId(clone $playlistChildren);
            $playlistChildren->clear();

            $this->collPlaylistsParents->remove($this->collPlaylistsParents->search($playlistsParent));

            if (null === $this->playlistsParentsScheduledForDeletion) {
                $this->playlistsParentsScheduledForDeletion = clone $this->collPlaylistsParents;
                $this->playlistsParentsScheduledForDeletion->clear();
            }

            $this->playlistsParentsScheduledForDeletion->push($playlistsParent);
        }


        return $this;
    }

    /**
     * Clears out the collEpisodes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEpisodes()
     */
    public function clearEpisodes()
    {
        $this->collEpisodes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collEpisodes crossRef collection.
     *
     * By default this just sets the collEpisodes collection to an empty collection (like clearEpisodes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initEpisodes()
    {
        $collectionClassName = PlaylistEpisodesTableMap::getTableMap()->getCollectionClassName();

        $this->collEpisodes = new $collectionClassName;
        $this->collEpisodesPartial = true;
        $this->collEpisodes->setModel('\Models\Episodes\Episodes');
    }

    /**
     * Checks if the collEpisodes collection is loaded.
     *
     * @return bool
     */
    public function isEpisodesLoaded()
    {
        return null !== $this->collEpisodes;
    }

    /**
     * Gets a collection of Episodes objects related by a many-to-many relationship
     * to the current object by way of the playlist_episodes cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlaylists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|Episodes[] List of Episodes objects
     */
    public function getEpisodes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEpisodesPartial && !$this->isNew();
        if (null === $this->collEpisodes || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collEpisodes) {
                    $this->initEpisodes();
                }
            } else {

                $query = EpisodesQuery::create(null, $criteria)
                    ->filterByPlaylist($this);
                $collEpisodes = $query->find($con);
                if (null !== $criteria) {
                    return $collEpisodes;
                }

                if ($partial && $this->collEpisodes) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collEpisodes as $obj) {
                        if (!$collEpisodes->contains($obj)) {
                            $collEpisodes[] = $obj;
                        }
                    }
                }

                $this->collEpisodes = $collEpisodes;
                $this->collEpisodesPartial = false;
            }
        }

        return $this->collEpisodes;
    }

    /**
     * Sets a collection of Episodes objects related by a many-to-many relationship
     * to the current object by way of the playlist_episodes cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $episodes A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function setEpisodes(Collection $episodes, ConnectionInterface $con = null)
    {
        $this->clearEpisodes();
        $currentEpisodes = $this->getEpisodes();

        $episodesScheduledForDeletion = $currentEpisodes->diff($episodes);

        foreach ($episodesScheduledForDeletion as $toDelete) {
            $this->removeEpisode($toDelete);
        }

        foreach ($episodes as $episode) {
            if (!$currentEpisodes->contains($episode)) {
                $this->doAddEpisode($episode);
            }
        }

        $this->collEpisodesPartial = false;
        $this->collEpisodes = $episodes;

        return $this;
    }

    /**
     * Gets the number of Episodes objects related by a many-to-many relationship
     * to the current object by way of the playlist_episodes cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Episodes objects
     */
    public function countEpisodes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEpisodesPartial && !$this->isNew();
        if (null === $this->collEpisodes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEpisodes) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getEpisodes());
                }

                $query = EpisodesQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPlaylist($this)
                    ->count($con);
            }
        } else {
            return count($this->collEpisodes);
        }
    }

    /**
     * Associate a Episodes to this object
     * through the playlist_episodes cross reference table.
     *
     * @param Episodes $episode
     * @return ChildPlaylists The current object (for fluent API support)
     */
    public function addEpisode(Episodes $episode)
    {
        if ($this->collEpisodes === null) {
            $this->initEpisodes();
        }

        if (!$this->getEpisodes()->contains($episode)) {
            // only add it if the **same** object is not already associated
            $this->collEpisodes->push($episode);
            $this->doAddEpisode($episode);
        }

        return $this;
    }

    /**
     *
     * @param Episodes $episode
     */
    protected function doAddEpisode(Episodes $episode)
    {
        $playlistEpisodes = new PlaylistEpisodes();

        $playlistEpisodes->setEpisode($episode);

        $playlistEpisodes->setPlaylist($this);

        $this->addPlaylistEpisodes($playlistEpisodes);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$episode->isPlaylistsLoaded()) {
            $episode->initPlaylists();
            $episode->getPlaylists()->push($this);
        } elseif (!$episode->getPlaylists()->contains($this)) {
            $episode->getPlaylists()->push($this);
        }

    }

    /**
     * Remove episode of this object
     * through the playlist_episodes cross reference table.
     *
     * @param Episodes $episode
     * @return ChildPlaylists The current object (for fluent API support)
     */
    public function removeEpisode(Episodes $episode)
    {
        if ($this->getEpisodes()->contains($episode)) {
            $playlistEpisodes = new PlaylistEpisodes();
            $playlistEpisodes->setEpisode($episode);
            if ($episode->isPlaylistsLoaded()) {
                //remove the back reference if available
                $episode->getPlaylists()->removeObject($this);
            }

            $playlistEpisodes->setPlaylist($this);
            $this->removePlaylistEpisodes(clone $playlistEpisodes);
            $playlistEpisodes->clear();

            $this->collEpisodes->remove($this->collEpisodes->search($episode));

            if (null === $this->episodesScheduledForDeletion) {
                $this->episodesScheduledForDeletion = clone $this->collEpisodes;
                $this->episodesScheduledForDeletion->clear();
            }

            $this->episodesScheduledForDeletion->push($episode);
        }


        return $this;
    }

    /**
     * Clears out the collPlaylistTagUsersPlaylistTagss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlaylistTagUsersPlaylistTagss()
     */
    public function clearPlaylistTagUsersPlaylistTagss()
    {
        $this->collPlaylistTagUsersPlaylistTagss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the combinationCollPlaylistTagUsersPlaylistTagss crossRef collection.
     *
     * By default this just sets the combinationCollPlaylistTagUsersPlaylistTagss collection to an empty collection (like clearPlaylistTagUsersPlaylistTagss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPlaylistTagUsersPlaylistTagss()
    {
        $this->combinationCollPlaylistTagUsersPlaylistTagss = new ObjectCombinationCollection;
        $this->combinationCollPlaylistTagUsersPlaylistTagssPartial = true;
    }

    /**
     * Checks if the combinationCollPlaylistTagUsersPlaylistTagss collection is loaded.
     *
     * @return bool
     */
    public function isPlaylistTagUsersPlaylistTagssLoaded()
    {
        return null !== $this->combinationCollPlaylistTagUsersPlaylistTagss;
    }

    /**
     * Gets a combined collection of Tags, Users objects related by a many-to-many relationship
     * to the current object by way of the user_playlist_tags cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlaylists is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCombinationCollection Combination list of Tags, Users objects
     */
    public function getPlaylistTagUsersPlaylistTagss($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollPlaylistTagUsersPlaylistTagssPartial && !$this->isNew();
        if (null === $this->combinationCollPlaylistTagUsersPlaylistTagss || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->combinationCollPlaylistTagUsersPlaylistTagss) {
                    $this->initPlaylistTagUsersPlaylistTagss();
                }
            } else {

                $query = UserPlaylistTagsQuery::create(null, $criteria)
                    ->filterByPlaylistsTags($this)
                    ->joinPlaylistTag()
                    ->joinUsersPlaylistTags()
                ;

                $items = $query->find($con);
                $combinationCollPlaylistTagUsersPlaylistTagss = new ObjectCombinationCollection();
                foreach ($items as $item) {
                    $combination = [];

                    $combination[] = $item->getPlaylistTag();
                    $combination[] = $item->getUsersPlaylistTags();
                    $combinationCollPlaylistTagUsersPlaylistTagss[] = $combination;
                }

                if (null !== $criteria) {
                    return $combinationCollPlaylistTagUsersPlaylistTagss;
                }

                if ($partial && $this->combinationCollPlaylistTagUsersPlaylistTagss) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->combinationCollPlaylistTagUsersPlaylistTagss as $obj) {
                        if (!call_user_func_array([$combinationCollPlaylistTagUsersPlaylistTagss, 'contains'], $obj)) {
                            $combinationCollPlaylistTagUsersPlaylistTagss[] = $obj;
                        }
                    }
                }

                $this->combinationCollPlaylistTagUsersPlaylistTagss = $combinationCollPlaylistTagUsersPlaylistTagss;
                $this->combinationCollPlaylistTagUsersPlaylistTagssPartial = false;
            }
        }

        return $this->combinationCollPlaylistTagUsersPlaylistTagss;
    }

    /**
     * Returns a not cached ObjectCollection of Tags objects. This will hit always the databases.
     * If you have attached new Tags object to this object you need to call `save` first to get
     * the correct return value. Use getPlaylistTagUsersPlaylistTagss() to get the current internal state.
     *
     * @param Users $usersPlaylistTags
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return Tags[]|ObjectCollection
     */
    public function getPlaylistTags(Users $usersPlaylistTags = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createPlaylistTagsQuery($usersPlaylistTags, $criteria)->find($con);
    }

    /**
     * Sets a collection of ChildTags, ChildUsers combination objects related by a many-to-many relationship
     * to the current object by way of the user_playlist_tags cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $playlistTagUsersPlaylistTagss A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildPlaylists The current object (for fluent API support)
     */
    public function setPlaylistTagUsersPlaylistTagss(Collection $playlistTagUsersPlaylistTagss, ConnectionInterface $con = null)
    {
        $this->clearPlaylistTagUsersPlaylistTagss();
        $currentPlaylistTagUsersPlaylistTagss = $this->getPlaylistTagUsersPlaylistTagss();

        $combinationCollPlaylistTagUsersPlaylistTagssScheduledForDeletion = $currentPlaylistTagUsersPlaylistTagss->diff($playlistTagUsersPlaylistTagss);

        foreach ($combinationCollPlaylistTagUsersPlaylistTagssScheduledForDeletion as $toDelete) {
            call_user_func_array([$this, 'removePlaylistTagUsersPlaylistTags'], $toDelete);
        }

        foreach ($playlistTagUsersPlaylistTagss as $playlistTagUsersPlaylistTags) {
            if (!call_user_func_array([$currentPlaylistTagUsersPlaylistTagss, 'contains'], $playlistTagUsersPlaylistTags)) {
                call_user_func_array([$this, 'doAddPlaylistTagUsersPlaylistTags'], $playlistTagUsersPlaylistTags);
            }
        }

        $this->combinationCollPlaylistTagUsersPlaylistTagssPartial = false;
        $this->combinationCollPlaylistTagUsersPlaylistTagss = $playlistTagUsersPlaylistTagss;

        return $this;
    }

    /**
     * Gets the number of ChildTags, ChildUsers combination objects related by a many-to-many relationship
     * to the current object by way of the user_playlist_tags cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildTags, ChildUsers combination objects
     */
    public function countPlaylistTagUsersPlaylistTagss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollPlaylistTagUsersPlaylistTagssPartial && !$this->isNew();
        if (null === $this->combinationCollPlaylistTagUsersPlaylistTagss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->combinationCollPlaylistTagUsersPlaylistTagss) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getPlaylistTagUsersPlaylistTagss());
                }

                $query = UserPlaylistTagsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPlaylistsTags($this)
                    ->count($con);
            }
        } else {
            return count($this->combinationCollPlaylistTagUsersPlaylistTagss);
        }
    }

    /**
     * Returns the not cached count of Tags objects. This will hit always the databases.
     * If you have attached new Tags object to this object you need to call `save` first to get
     * the correct return value. Use getPlaylistTagUsersPlaylistTagss() to get the current internal state.
     *
     * @param Users $usersPlaylistTags
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return integer
     */
    public function countPlaylistTags(Users $usersPlaylistTags = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createPlaylistTagsQuery($usersPlaylistTags, $criteria)->count($con);
    }

    /**
     * Associate a Tags to this object
     * through the user_playlist_tags cross reference table.
     *
     * @param Tags $playlistTag,
     * @param Users $usersPlaylistTags
     * @return ChildPlaylists The current object (for fluent API support)
     */
    public function addPlaylistTag(Tags $playlistTag, Users $usersPlaylistTags)
    {
        if ($this->combinationCollPlaylistTagUsersPlaylistTagss === null) {
            $this->initPlaylistTagUsersPlaylistTagss();
        }

        if (!$this->getPlaylistTagUsersPlaylistTagss()->contains($playlistTag, $usersPlaylistTags)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollPlaylistTagUsersPlaylistTagss->push($playlistTag, $usersPlaylistTags);
            $this->doAddPlaylistTagUsersPlaylistTags($playlistTag, $usersPlaylistTags);
        }

        return $this;
    }

    /**
     * Associate a Users to this object
     * through the user_playlist_tags cross reference table.
     *
     * @param Users $usersPlaylistTags,
     * @param Tags $playlistTag
     * @return ChildPlaylists The current object (for fluent API support)
     */
    public function addUsersPlaylistTags(Users $usersPlaylistTags, Tags $playlistTag)
    {
        if ($this->combinationCollPlaylistTagUsersPlaylistTagss === null) {
            $this->initPlaylistTagUsersPlaylistTagss();
        }

        if (!$this->getPlaylistTagUsersPlaylistTagss()->contains($usersPlaylistTags, $playlistTag)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollPlaylistTagUsersPlaylistTagss->push($usersPlaylistTags, $playlistTag);
            $this->doAddPlaylistTagUsersPlaylistTags($usersPlaylistTags, $playlistTag);
        }

        return $this;
    }

    /**
     *
     * @param Tags $playlistTag,
     * @param Users $usersPlaylistTags
     */
    protected function doAddPlaylistTagUsersPlaylistTags(Tags $playlistTag, Users $usersPlaylistTags)
    {
        $userPlaylistTags = new UserPlaylistTags();

        $userPlaylistTags->setPlaylistTag($playlistTag);
        $userPlaylistTags->setUsersPlaylistTags($usersPlaylistTags);

        $userPlaylistTags->setPlaylistsTags($this);

        $this->addUserPlaylistTags($userPlaylistTags);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($playlistTag->isPlaylistsTagsUsersPlaylistTagssLoaded()) {
            $playlistTag->initPlaylistsTagsUsersPlaylistTagss();
            $playlistTag->getPlaylistsTagsUsersPlaylistTagss()->push($this, $usersPlaylistTags);
        } elseif (!$playlistTag->getPlaylistsTagsUsersPlaylistTagss()->contains($this, $usersPlaylistTags)) {
            $playlistTag->getPlaylistsTagsUsersPlaylistTagss()->push($this, $usersPlaylistTags);
        }

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($usersPlaylistTags->isPlaylistsTagsPlaylistTagsLoaded()) {
            $usersPlaylistTags->initPlaylistsTagsPlaylistTags();
            $usersPlaylistTags->getPlaylistsTagsPlaylistTags()->push($this, $playlistTag);
        } elseif (!$usersPlaylistTags->getPlaylistsTagsPlaylistTags()->contains($this, $playlistTag)) {
            $usersPlaylistTags->getPlaylistsTagsPlaylistTags()->push($this, $playlistTag);
        }

    }

    /**
     * Remove playlistTag, usersPlaylistTags of this object
     * through the user_playlist_tags cross reference table.
     *
     * @param Tags $playlistTag,
     * @param Users $usersPlaylistTags
     * @return ChildPlaylists The current object (for fluent API support)
     */
    public function removePlaylistTagUsersPlaylistTags(Tags $playlistTag, Users $usersPlaylistTags)
    {
        if ($this->getPlaylistTagUsersPlaylistTagss()->contains($playlistTag, $usersPlaylistTags)) {
            $userPlaylistTags = new UserPlaylistTags();
            $userPlaylistTags->setPlaylistTag($playlistTag);
            if ($playlistTag->isPlaylistsTagsUsersPlaylistTagssLoaded()) {
                //remove the back reference if available
                $playlistTag->getPlaylistsTagsUsersPlaylistTagss()->removeObject($this, $usersPlaylistTags);
            }

            $userPlaylistTags->setUsersPlaylistTags($usersPlaylistTags);
            if ($usersPlaylistTags->isPlaylistsTagsPlaylistTagsLoaded()) {
                //remove the back reference if available
                $usersPlaylistTags->getPlaylistsTagsPlaylistTags()->removeObject($this, $playlistTag);
            }

            $userPlaylistTags->setPlaylistsTags($this);
            $this->removeUserPlaylistTags(clone $userPlaylistTags);
            $userPlaylistTags->clear();

            $this->combinationCollPlaylistTagUsersPlaylistTagss->remove($this->combinationCollPlaylistTagUsersPlaylistTagss->search($playlistTag, $usersPlaylistTags));

            if (null === $this->combinationCollPlaylistTagUsersPlaylistTagssScheduledForDeletion) {
                $this->combinationCollPlaylistTagUsersPlaylistTagssScheduledForDeletion = clone $this->combinationCollPlaylistTagUsersPlaylistTagss;
                $this->combinationCollPlaylistTagUsersPlaylistTagssScheduledForDeletion->clear();
            }

            $this->combinationCollPlaylistTagUsersPlaylistTagssScheduledForDeletion->push($playlistTag, $usersPlaylistTags);
        }


        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->is_parent = null;
        $this->tag_generated = null;
        $this->favorites = null;
        $this->shareable_status = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collUserPlaylistss) {
                foreach ($this->collUserPlaylistss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlaylistChildrensRelatedByParentId) {
                foreach ($this->collPlaylistChildrensRelatedByParentId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlaylistChildrensRelatedByChildId) {
                foreach ($this->collPlaylistChildrensRelatedByChildId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlaylistCommentss) {
                foreach ($this->collPlaylistCommentss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlaylistEpisodess) {
                foreach ($this->collPlaylistEpisodess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserPlaylistTagss) {
                foreach ($this->collUserPlaylistTagss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserss) {
                foreach ($this->collUserss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlaylistschildren) {
                foreach ($this->collPlaylistschildren as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlaylistsParents) {
                foreach ($this->collPlaylistsParents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEpisodes) {
                foreach ($this->collEpisodes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->combinationCollPlaylistTagUsersPlaylistTagss) {
                foreach ($this->combinationCollPlaylistTagUsersPlaylistTagss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collUserPlaylistss = null;
        $this->collPlaylistChildrensRelatedByParentId = null;
        $this->collPlaylistChildrensRelatedByChildId = null;
        $this->collPlaylistCommentss = null;
        $this->collPlaylistEpisodess = null;
        $this->collUserPlaylistTagss = null;
        $this->collUserss = null;
        $this->collPlaylistschildren = null;
        $this->collPlaylistsParents = null;
        $this->collEpisodes = null;
        $this->combinationCollPlaylistTagUsersPlaylistTagss = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PlaylistsTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildPlaylists The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[PlaylistsTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
