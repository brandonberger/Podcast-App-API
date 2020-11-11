<?php

namespace Models\Episodes\Base;

use \DateTime;
use \Exception;
use \PDO;
use Models\Bookmarks\Bookmarks;
use Models\Bookmarks\BookmarksQuery;
use Models\Bookmarks\Base\Bookmarks as BaseBookmarks;
use Models\Bookmarks\Map\BookmarksTableMap;
use Models\Episodes\Episodes as ChildEpisodes;
use Models\Episodes\EpisodesQuery as ChildEpisodesQuery;
use Models\Episodes\PlaylistEpisodes as ChildPlaylistEpisodes;
use Models\Episodes\PlaylistEpisodesQuery as ChildPlaylistEpisodesQuery;
use Models\Episodes\UserEpisodes as ChildUserEpisodes;
use Models\Episodes\UserEpisodesQuery as ChildUserEpisodesQuery;
use Models\Episodes\Map\EpisodesTableMap;
use Models\Episodes\Map\PlaylistEpisodesTableMap;
use Models\Episodes\Map\UserEpisodesTableMap;
use Models\Playlists\Playlists;
use Models\Playlists\PlaylistsQuery;
use Models\Podcasts\Podcasts;
use Models\Podcasts\PodcastsQuery;
use Models\Tags\Tags;
use Models\UserTags\UserEpisodeTags;
use Models\UserTags\UserEpisodeTagsQuery;
use Models\UserTags\Base\UserEpisodeTags as BaseUserEpisodeTags;
use Models\UserTags\Map\UserEpisodeTagsTableMap;
use Models\Users\Users;
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
 * Base class that represents a row from the 'episodes' table.
 *
 *
 *
 * @package    propel.generator.Models.Episodes.Base
 */
abstract class Episodes implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Episodes\\Map\\EpisodesTableMap';


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
     * The value for the episode_id field.
     *
     * @var        string
     */
    protected $episode_id;

    /**
     * The value for the podcast_id field.
     *
     * @var        string
     */
    protected $podcast_id;

    /**
     * The value for the number_of_plays field.
     *
     * @var        int
     */
    protected $number_of_plays;

    /**
     * The value for the number_of_downloads field.
     *
     * @var        int
     */
    protected $number_of_downloads;

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
     * @var        Podcasts
     */
    protected $aPodcasts;

    /**
     * @var        ObjectCollection|ChildUserEpisodes[] Collection to store aggregation of ChildUserEpisodes objects.
     */
    protected $collUserEpisodess;
    protected $collUserEpisodessPartial;

    /**
     * @var        ObjectCollection|ChildPlaylistEpisodes[] Collection to store aggregation of ChildPlaylistEpisodes objects.
     */
    protected $collPlaylistEpisodess;
    protected $collPlaylistEpisodessPartial;

    /**
     * @var        ObjectCollection|Bookmarks[] Collection to store aggregation of Bookmarks objects.
     */
    protected $collBookmarkss;
    protected $collBookmarkssPartial;

    /**
     * @var        ObjectCollection|UserEpisodeTags[] Collection to store aggregation of UserEpisodeTags objects.
     */
    protected $collUserEpisodeTagss;
    protected $collUserEpisodeTagssPartial;

    /**
     * @var        ObjectCollection|Playlists[] Cross Collection to store aggregation of Playlists objects.
     */
    protected $collPlaylists;

    /**
     * @var bool
     */
    protected $collPlaylistsPartial;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildTags, ChildUsers combination combinations.
     */
    protected $combinationCollEpisodeTagUsersEpisodeTagss;

    /**
     * @var bool
     */
    protected $combinationCollEpisodeTagUsersEpisodeTagssPartial;

    /**
     * @var        ObjectCollection|Tags[] Cross Collection to store aggregation of Tags objects.
     */
    protected $collEpisodeTags;

    /**
     * @var bool
     */
    protected $collEpisodeTagsPartial;

    /**
     * @var        ObjectCollection|Users[] Cross Collection to store aggregation of Users objects.
     */
    protected $collUsersEpisodeTagss;

    /**
     * @var bool
     */
    protected $collUsersEpisodeTagssPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Playlists[]
     */
    protected $playlistsScheduledForDeletion = null;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildTags, ChildUsers combination combinations.
     */
    protected $combinationCollEpisodeTagUsersEpisodeTagssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserEpisodes[]
     */
    protected $userEpisodessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPlaylistEpisodes[]
     */
    protected $playlistEpisodessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Bookmarks[]
     */
    protected $bookmarkssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|UserEpisodeTags[]
     */
    protected $userEpisodeTagssScheduledForDeletion = null;

    /**
     * Initializes internal state of Models\Episodes\Base\Episodes object.
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
     * Compares this with another <code>Episodes</code> instance.  If
     * <code>obj</code> is an instance of <code>Episodes</code>, delegates to
     * <code>equals(Episodes)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Episodes The current object, for fluid interface
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
     * Get the [episode_id] column value.
     *
     * @return string
     */
    public function getEpisodeId()
    {
        return $this->episode_id;
    }

    /**
     * Get the [podcast_id] column value.
     *
     * @return string
     */
    public function getPodcastId()
    {
        return $this->podcast_id;
    }

    /**
     * Get the [number_of_plays] column value.
     *
     * @return int
     */
    public function getNumberOfPlays()
    {
        return $this->number_of_plays;
    }

    /**
     * Get the [number_of_downloads] column value.
     *
     * @return int
     */
    public function getNumberOfDownloads()
    {
        return $this->number_of_downloads;
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
     * @return $this|\Models\Episodes\Episodes The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[EpisodesTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [episode_id] column.
     *
     * @param string $v new value
     * @return $this|\Models\Episodes\Episodes The current object (for fluent API support)
     */
    public function setEpisodeId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->episode_id !== $v) {
            $this->episode_id = $v;
            $this->modifiedColumns[EpisodesTableMap::COL_EPISODE_ID] = true;
        }

        return $this;
    } // setEpisodeId()

    /**
     * Set the value of [podcast_id] column.
     *
     * @param string $v new value
     * @return $this|\Models\Episodes\Episodes The current object (for fluent API support)
     */
    public function setPodcastId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->podcast_id !== $v) {
            $this->podcast_id = $v;
            $this->modifiedColumns[EpisodesTableMap::COL_PODCAST_ID] = true;
        }

        if ($this->aPodcasts !== null && $this->aPodcasts->getId() !== $v) {
            $this->aPodcasts = null;
        }

        return $this;
    } // setPodcastId()

    /**
     * Set the value of [number_of_plays] column.
     *
     * @param int $v new value
     * @return $this|\Models\Episodes\Episodes The current object (for fluent API support)
     */
    public function setNumberOfPlays($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->number_of_plays !== $v) {
            $this->number_of_plays = $v;
            $this->modifiedColumns[EpisodesTableMap::COL_NUMBER_OF_PLAYS] = true;
        }

        return $this;
    } // setNumberOfPlays()

    /**
     * Set the value of [number_of_downloads] column.
     *
     * @param int $v new value
     * @return $this|\Models\Episodes\Episodes The current object (for fluent API support)
     */
    public function setNumberOfDownloads($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->number_of_downloads !== $v) {
            $this->number_of_downloads = $v;
            $this->modifiedColumns[EpisodesTableMap::COL_NUMBER_OF_DOWNLOADS] = true;
        }

        return $this;
    } // setNumberOfDownloads()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Episodes\Episodes The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EpisodesTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Episodes\Episodes The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EpisodesTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EpisodesTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EpisodesTableMap::translateFieldName('EpisodeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->episode_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EpisodesTableMap::translateFieldName('PodcastId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->podcast_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EpisodesTableMap::translateFieldName('NumberOfPlays', TableMap::TYPE_PHPNAME, $indexType)];
            $this->number_of_plays = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : EpisodesTableMap::translateFieldName('NumberOfDownloads', TableMap::TYPE_PHPNAME, $indexType)];
            $this->number_of_downloads = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : EpisodesTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : EpisodesTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = EpisodesTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Episodes\\Episodes'), 0, $e);
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
        if ($this->aPodcasts !== null && $this->podcast_id !== $this->aPodcasts->getId()) {
            $this->aPodcasts = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(EpisodesTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEpisodesQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPodcasts = null;
            $this->collUserEpisodess = null;

            $this->collPlaylistEpisodess = null;

            $this->collBookmarkss = null;

            $this->collUserEpisodeTagss = null;

            $this->collPlaylists = null;
            $this->collEpisodeTagUsersEpisodeTagss = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Episodes::setDeleted()
     * @see Episodes::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EpisodesTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEpisodesQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(EpisodesTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(EpisodesTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(EpisodesTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(EpisodesTableMap::COL_UPDATED_AT)) {
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
                EpisodesTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPodcasts !== null) {
                if ($this->aPodcasts->isModified() || $this->aPodcasts->isNew()) {
                    $affectedRows += $this->aPodcasts->save($con);
                }
                $this->setPodcasts($this->aPodcasts);
            }

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

            if ($this->playlistsScheduledForDeletion !== null) {
                if (!$this->playlistsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->playlistsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\Episodes\PlaylistEpisodesQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->playlistsScheduledForDeletion = null;
                }

            }

            if ($this->collPlaylists) {
                foreach ($this->collPlaylists as $playlist) {
                    if (!$playlist->isDeleted() && ($playlist->isNew() || $playlist->isModified())) {
                        $playlist->save($con);
                    }
                }
            }


            if ($this->combinationCollEpisodeTagUsersEpisodeTagssScheduledForDeletion !== null) {
                if (!$this->combinationCollEpisodeTagUsersEpisodeTagssScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->combinationCollEpisodeTagUsersEpisodeTagssScheduledForDeletion as $combination) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $combination[0]->getId();
                        $entryPk[2] = $combination[1]->getId();

                        $pks[] = $entryPk;
                    }

                    \Models\UserTags\UserEpisodeTagsQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->combinationCollEpisodeTagUsersEpisodeTagssScheduledForDeletion = null;
                }

            }

            if (null !== $this->combinationCollEpisodeTagUsersEpisodeTagss) {
                foreach ($this->combinationCollEpisodeTagUsersEpisodeTagss as $combination) {

                    //$combination[0] = Tags (user_episode_tags_fk_6bac06)
                    if (!$combination[0]->isDeleted() && ($combination[0]->isNew() || $combination[0]->isModified())) {
                        $combination[0]->save($con);
                    }

                    //$combination[1] = Users (user_episode_tags_fk_69bd79)
                    if (!$combination[1]->isDeleted() && ($combination[1]->isNew() || $combination[1]->isModified())) {
                        $combination[1]->save($con);
                    }

                }
            }


            if ($this->userEpisodessScheduledForDeletion !== null) {
                if (!$this->userEpisodessScheduledForDeletion->isEmpty()) {
                    \Models\Episodes\UserEpisodesQuery::create()
                        ->filterByPrimaryKeys($this->userEpisodessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userEpisodessScheduledForDeletion = null;
                }
            }

            if ($this->collUserEpisodess !== null) {
                foreach ($this->collUserEpisodess as $referrerFK) {
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

            if ($this->bookmarkssScheduledForDeletion !== null) {
                if (!$this->bookmarkssScheduledForDeletion->isEmpty()) {
                    \Models\Bookmarks\BookmarksQuery::create()
                        ->filterByPrimaryKeys($this->bookmarkssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->bookmarkssScheduledForDeletion = null;
                }
            }

            if ($this->collBookmarkss !== null) {
                foreach ($this->collBookmarkss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userEpisodeTagssScheduledForDeletion !== null) {
                if (!$this->userEpisodeTagssScheduledForDeletion->isEmpty()) {
                    \Models\UserTags\UserEpisodeTagsQuery::create()
                        ->filterByPrimaryKeys($this->userEpisodeTagssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userEpisodeTagssScheduledForDeletion = null;
                }
            }

            if ($this->collUserEpisodeTagss !== null) {
                foreach ($this->collUserEpisodeTagss as $referrerFK) {
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

        $this->modifiedColumns[EpisodesTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EpisodesTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EpisodesTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(EpisodesTableMap::COL_EPISODE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'episode_id';
        }
        if ($this->isColumnModified(EpisodesTableMap::COL_PODCAST_ID)) {
            $modifiedColumns[':p' . $index++]  = 'podcast_id';
        }
        if ($this->isColumnModified(EpisodesTableMap::COL_NUMBER_OF_PLAYS)) {
            $modifiedColumns[':p' . $index++]  = 'number_of_plays';
        }
        if ($this->isColumnModified(EpisodesTableMap::COL_NUMBER_OF_DOWNLOADS)) {
            $modifiedColumns[':p' . $index++]  = 'number_of_downloads';
        }
        if ($this->isColumnModified(EpisodesTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(EpisodesTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO episodes (%s) VALUES (%s)',
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
                    case 'episode_id':
                        $stmt->bindValue($identifier, $this->episode_id, PDO::PARAM_STR);
                        break;
                    case 'podcast_id':
                        $stmt->bindValue($identifier, $this->podcast_id, PDO::PARAM_STR);
                        break;
                    case 'number_of_plays':
                        $stmt->bindValue($identifier, $this->number_of_plays, PDO::PARAM_INT);
                        break;
                    case 'number_of_downloads':
                        $stmt->bindValue($identifier, $this->number_of_downloads, PDO::PARAM_INT);
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
        $pos = EpisodesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEpisodeId();
                break;
            case 2:
                return $this->getPodcastId();
                break;
            case 3:
                return $this->getNumberOfPlays();
                break;
            case 4:
                return $this->getNumberOfDownloads();
                break;
            case 5:
                return $this->getCreatedAt();
                break;
            case 6:
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

        if (isset($alreadyDumpedObjects['Episodes'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Episodes'][$this->hashCode()] = true;
        $keys = EpisodesTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getEpisodeId(),
            $keys[2] => $this->getPodcastId(),
            $keys[3] => $this->getNumberOfPlays(),
            $keys[4] => $this->getNumberOfDownloads(),
            $keys[5] => $this->getCreatedAt(),
            $keys[6] => $this->getUpdatedAt(),
        );
        if ($result[$keys[5]] instanceof \DateTimeInterface) {
            $result[$keys[5]] = $result[$keys[5]]->format('c');
        }

        if ($result[$keys[6]] instanceof \DateTimeInterface) {
            $result[$keys[6]] = $result[$keys[6]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPodcasts) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'podcasts';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'podcasts';
                        break;
                    default:
                        $key = 'Podcasts';
                }

                $result[$key] = $this->aPodcasts->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collUserEpisodess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userEpisodess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_episodess';
                        break;
                    default:
                        $key = 'UserEpisodess';
                }

                $result[$key] = $this->collUserEpisodess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collBookmarkss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'bookmarkss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'bookmarkss';
                        break;
                    default:
                        $key = 'Bookmarkss';
                }

                $result[$key] = $this->collBookmarkss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserEpisodeTagss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userEpisodeTagss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_episode_tagss';
                        break;
                    default:
                        $key = 'UserEpisodeTagss';
                }

                $result[$key] = $this->collUserEpisodeTagss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Models\Episodes\Episodes
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EpisodesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\Episodes\Episodes
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setEpisodeId($value);
                break;
            case 2:
                $this->setPodcastId($value);
                break;
            case 3:
                $this->setNumberOfPlays($value);
                break;
            case 4:
                $this->setNumberOfDownloads($value);
                break;
            case 5:
                $this->setCreatedAt($value);
                break;
            case 6:
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
        $keys = EpisodesTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEpisodeId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPodcastId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setNumberOfPlays($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setNumberOfDownloads($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCreatedAt($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setUpdatedAt($arr[$keys[6]]);
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
     * @return $this|\Models\Episodes\Episodes The current object, for fluid interface
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
        $criteria = new Criteria(EpisodesTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EpisodesTableMap::COL_ID)) {
            $criteria->add(EpisodesTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(EpisodesTableMap::COL_EPISODE_ID)) {
            $criteria->add(EpisodesTableMap::COL_EPISODE_ID, $this->episode_id);
        }
        if ($this->isColumnModified(EpisodesTableMap::COL_PODCAST_ID)) {
            $criteria->add(EpisodesTableMap::COL_PODCAST_ID, $this->podcast_id);
        }
        if ($this->isColumnModified(EpisodesTableMap::COL_NUMBER_OF_PLAYS)) {
            $criteria->add(EpisodesTableMap::COL_NUMBER_OF_PLAYS, $this->number_of_plays);
        }
        if ($this->isColumnModified(EpisodesTableMap::COL_NUMBER_OF_DOWNLOADS)) {
            $criteria->add(EpisodesTableMap::COL_NUMBER_OF_DOWNLOADS, $this->number_of_downloads);
        }
        if ($this->isColumnModified(EpisodesTableMap::COL_CREATED_AT)) {
            $criteria->add(EpisodesTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(EpisodesTableMap::COL_UPDATED_AT)) {
            $criteria->add(EpisodesTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildEpisodesQuery::create();
        $criteria->add(EpisodesTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Models\Episodes\Episodes (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEpisodeId($this->getEpisodeId());
        $copyObj->setPodcastId($this->getPodcastId());
        $copyObj->setNumberOfPlays($this->getNumberOfPlays());
        $copyObj->setNumberOfDownloads($this->getNumberOfDownloads());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUserEpisodess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserEpisodes($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlaylistEpisodess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlaylistEpisodes($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getBookmarkss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBookmarks($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserEpisodeTagss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserEpisodeTags($relObj->copy($deepCopy));
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
     * @return \Models\Episodes\Episodes Clone of current object.
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
     * Declares an association between this object and a Podcasts object.
     *
     * @param  Podcasts $v
     * @return $this|\Models\Episodes\Episodes The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPodcasts(Podcasts $v = null)
    {
        if ($v === null) {
            $this->setPodcastId(NULL);
        } else {
            $this->setPodcastId($v->getId());
        }

        $this->aPodcasts = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Podcasts object, it will not be re-added.
        if ($v !== null) {
            $v->addEpisodes($this);
        }


        return $this;
    }


    /**
     * Get the associated Podcasts object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return Podcasts The associated Podcasts object.
     * @throws PropelException
     */
    public function getPodcasts(ConnectionInterface $con = null)
    {
        if ($this->aPodcasts === null && (($this->podcast_id !== "" && $this->podcast_id !== null))) {
            $this->aPodcasts = PodcastsQuery::create()->findPk($this->podcast_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPodcasts->addEpisodess($this);
             */
        }

        return $this->aPodcasts;
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
        if ('UserEpisodes' == $relationName) {
            $this->initUserEpisodess();
            return;
        }
        if ('PlaylistEpisodes' == $relationName) {
            $this->initPlaylistEpisodess();
            return;
        }
        if ('Bookmarks' == $relationName) {
            $this->initBookmarkss();
            return;
        }
        if ('UserEpisodeTags' == $relationName) {
            $this->initUserEpisodeTagss();
            return;
        }
    }

    /**
     * Clears out the collUserEpisodess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserEpisodess()
     */
    public function clearUserEpisodess()
    {
        $this->collUserEpisodess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserEpisodess collection loaded partially.
     */
    public function resetPartialUserEpisodess($v = true)
    {
        $this->collUserEpisodessPartial = $v;
    }

    /**
     * Initializes the collUserEpisodess collection.
     *
     * By default this just sets the collUserEpisodess collection to an empty array (like clearcollUserEpisodess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserEpisodess($overrideExisting = true)
    {
        if (null !== $this->collUserEpisodess && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserEpisodesTableMap::getTableMap()->getCollectionClassName();

        $this->collUserEpisodess = new $collectionClassName;
        $this->collUserEpisodess->setModel('\Models\Episodes\UserEpisodes');
    }

    /**
     * Gets an array of ChildUserEpisodes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEpisodes is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserEpisodes[] List of ChildUserEpisodes objects
     * @throws PropelException
     */
    public function getUserEpisodess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserEpisodessPartial && !$this->isNew();
        if (null === $this->collUserEpisodess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserEpisodess) {
                // return empty collection
                $this->initUserEpisodess();
            } else {
                $collUserEpisodess = ChildUserEpisodesQuery::create(null, $criteria)
                    ->filterByEpisode($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserEpisodessPartial && count($collUserEpisodess)) {
                        $this->initUserEpisodess(false);

                        foreach ($collUserEpisodess as $obj) {
                            if (false == $this->collUserEpisodess->contains($obj)) {
                                $this->collUserEpisodess->append($obj);
                            }
                        }

                        $this->collUserEpisodessPartial = true;
                    }

                    return $collUserEpisodess;
                }

                if ($partial && $this->collUserEpisodess) {
                    foreach ($this->collUserEpisodess as $obj) {
                        if ($obj->isNew()) {
                            $collUserEpisodess[] = $obj;
                        }
                    }
                }

                $this->collUserEpisodess = $collUserEpisodess;
                $this->collUserEpisodessPartial = false;
            }
        }

        return $this->collUserEpisodess;
    }

    /**
     * Sets a collection of ChildUserEpisodes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userEpisodess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEpisodes The current object (for fluent API support)
     */
    public function setUserEpisodess(Collection $userEpisodess, ConnectionInterface $con = null)
    {
        /** @var ChildUserEpisodes[] $userEpisodessToDelete */
        $userEpisodessToDelete = $this->getUserEpisodess(new Criteria(), $con)->diff($userEpisodess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userEpisodessScheduledForDeletion = clone $userEpisodessToDelete;

        foreach ($userEpisodessToDelete as $userEpisodesRemoved) {
            $userEpisodesRemoved->setEpisode(null);
        }

        $this->collUserEpisodess = null;
        foreach ($userEpisodess as $userEpisodes) {
            $this->addUserEpisodes($userEpisodes);
        }

        $this->collUserEpisodess = $userEpisodess;
        $this->collUserEpisodessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserEpisodes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserEpisodes objects.
     * @throws PropelException
     */
    public function countUserEpisodess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserEpisodessPartial && !$this->isNew();
        if (null === $this->collUserEpisodess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserEpisodess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserEpisodess());
            }

            $query = ChildUserEpisodesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEpisode($this)
                ->count($con);
        }

        return count($this->collUserEpisodess);
    }

    /**
     * Method called to associate a ChildUserEpisodes object to this object
     * through the ChildUserEpisodes foreign key attribute.
     *
     * @param  ChildUserEpisodes $l ChildUserEpisodes
     * @return $this|\Models\Episodes\Episodes The current object (for fluent API support)
     */
    public function addUserEpisodes(ChildUserEpisodes $l)
    {
        if ($this->collUserEpisodess === null) {
            $this->initUserEpisodess();
            $this->collUserEpisodessPartial = true;
        }

        if (!$this->collUserEpisodess->contains($l)) {
            $this->doAddUserEpisodes($l);

            if ($this->userEpisodessScheduledForDeletion and $this->userEpisodessScheduledForDeletion->contains($l)) {
                $this->userEpisodessScheduledForDeletion->remove($this->userEpisodessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserEpisodes $userEpisodes The ChildUserEpisodes object to add.
     */
    protected function doAddUserEpisodes(ChildUserEpisodes $userEpisodes)
    {
        $this->collUserEpisodess[]= $userEpisodes;
        $userEpisodes->setEpisode($this);
    }

    /**
     * @param  ChildUserEpisodes $userEpisodes The ChildUserEpisodes object to remove.
     * @return $this|ChildEpisodes The current object (for fluent API support)
     */
    public function removeUserEpisodes(ChildUserEpisodes $userEpisodes)
    {
        if ($this->getUserEpisodess()->contains($userEpisodes)) {
            $pos = $this->collUserEpisodess->search($userEpisodes);
            $this->collUserEpisodess->remove($pos);
            if (null === $this->userEpisodessScheduledForDeletion) {
                $this->userEpisodessScheduledForDeletion = clone $this->collUserEpisodess;
                $this->userEpisodessScheduledForDeletion->clear();
            }
            $this->userEpisodessScheduledForDeletion[]= clone $userEpisodes;
            $userEpisodes->setEpisode(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Episodes is new, it will return
     * an empty collection; or if this Episodes has previously
     * been saved, it will retrieve related UserEpisodess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Episodes.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserEpisodes[] List of ChildUserEpisodes objects
     */
    public function getUserEpisodessJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserEpisodesQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getUserEpisodess($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Episodes is new, it will return
     * an empty collection; or if this Episodes has previously
     * been saved, it will retrieve related UserEpisodess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Episodes.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserEpisodes[] List of ChildUserEpisodes objects
     */
    public function getUserEpisodessJoinBookmark(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserEpisodesQuery::create(null, $criteria);
        $query->joinWith('Bookmark', $joinBehavior);

        return $this->getUserEpisodess($query, $con);
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
     * Gets an array of ChildPlaylistEpisodes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEpisodes is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPlaylistEpisodes[] List of ChildPlaylistEpisodes objects
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
                $collPlaylistEpisodess = ChildPlaylistEpisodesQuery::create(null, $criteria)
                    ->filterByEpisode($this)
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
     * Sets a collection of ChildPlaylistEpisodes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playlistEpisodess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEpisodes The current object (for fluent API support)
     */
    public function setPlaylistEpisodess(Collection $playlistEpisodess, ConnectionInterface $con = null)
    {
        /** @var ChildPlaylistEpisodes[] $playlistEpisodessToDelete */
        $playlistEpisodessToDelete = $this->getPlaylistEpisodess(new Criteria(), $con)->diff($playlistEpisodess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playlistEpisodessScheduledForDeletion = clone $playlistEpisodessToDelete;

        foreach ($playlistEpisodessToDelete as $playlistEpisodesRemoved) {
            $playlistEpisodesRemoved->setEpisode(null);
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
     * Returns the number of related PlaylistEpisodes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PlaylistEpisodes objects.
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

            $query = ChildPlaylistEpisodesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEpisode($this)
                ->count($con);
        }

        return count($this->collPlaylistEpisodess);
    }

    /**
     * Method called to associate a ChildPlaylistEpisodes object to this object
     * through the ChildPlaylistEpisodes foreign key attribute.
     *
     * @param  ChildPlaylistEpisodes $l ChildPlaylistEpisodes
     * @return $this|\Models\Episodes\Episodes The current object (for fluent API support)
     */
    public function addPlaylistEpisodes(ChildPlaylistEpisodes $l)
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
     * @param ChildPlaylistEpisodes $playlistEpisodes The ChildPlaylistEpisodes object to add.
     */
    protected function doAddPlaylistEpisodes(ChildPlaylistEpisodes $playlistEpisodes)
    {
        $this->collPlaylistEpisodess[]= $playlistEpisodes;
        $playlistEpisodes->setEpisode($this);
    }

    /**
     * @param  ChildPlaylistEpisodes $playlistEpisodes The ChildPlaylistEpisodes object to remove.
     * @return $this|ChildEpisodes The current object (for fluent API support)
     */
    public function removePlaylistEpisodes(ChildPlaylistEpisodes $playlistEpisodes)
    {
        if ($this->getPlaylistEpisodess()->contains($playlistEpisodes)) {
            $pos = $this->collPlaylistEpisodess->search($playlistEpisodes);
            $this->collPlaylistEpisodess->remove($pos);
            if (null === $this->playlistEpisodessScheduledForDeletion) {
                $this->playlistEpisodessScheduledForDeletion = clone $this->collPlaylistEpisodess;
                $this->playlistEpisodessScheduledForDeletion->clear();
            }
            $this->playlistEpisodessScheduledForDeletion[]= clone $playlistEpisodes;
            $playlistEpisodes->setEpisode(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Episodes is new, it will return
     * an empty collection; or if this Episodes has previously
     * been saved, it will retrieve related PlaylistEpisodess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Episodes.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPlaylistEpisodes[] List of ChildPlaylistEpisodes objects
     */
    public function getPlaylistEpisodessJoinPlaylist(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPlaylistEpisodesQuery::create(null, $criteria);
        $query->joinWith('Playlist', $joinBehavior);

        return $this->getPlaylistEpisodess($query, $con);
    }

    /**
     * Clears out the collBookmarkss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addBookmarkss()
     */
    public function clearBookmarkss()
    {
        $this->collBookmarkss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collBookmarkss collection loaded partially.
     */
    public function resetPartialBookmarkss($v = true)
    {
        $this->collBookmarkssPartial = $v;
    }

    /**
     * Initializes the collBookmarkss collection.
     *
     * By default this just sets the collBookmarkss collection to an empty array (like clearcollBookmarkss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBookmarkss($overrideExisting = true)
    {
        if (null !== $this->collBookmarkss && !$overrideExisting) {
            return;
        }

        $collectionClassName = BookmarksTableMap::getTableMap()->getCollectionClassName();

        $this->collBookmarkss = new $collectionClassName;
        $this->collBookmarkss->setModel('\Models\Bookmarks\Bookmarks');
    }

    /**
     * Gets an array of Bookmarks objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEpisodes is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Bookmarks[] List of Bookmarks objects
     * @throws PropelException
     */
    public function getBookmarkss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collBookmarkssPartial && !$this->isNew();
        if (null === $this->collBookmarkss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBookmarkss) {
                // return empty collection
                $this->initBookmarkss();
            } else {
                $collBookmarkss = BookmarksQuery::create(null, $criteria)
                    ->filterByEpisodes($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collBookmarkssPartial && count($collBookmarkss)) {
                        $this->initBookmarkss(false);

                        foreach ($collBookmarkss as $obj) {
                            if (false == $this->collBookmarkss->contains($obj)) {
                                $this->collBookmarkss->append($obj);
                            }
                        }

                        $this->collBookmarkssPartial = true;
                    }

                    return $collBookmarkss;
                }

                if ($partial && $this->collBookmarkss) {
                    foreach ($this->collBookmarkss as $obj) {
                        if ($obj->isNew()) {
                            $collBookmarkss[] = $obj;
                        }
                    }
                }

                $this->collBookmarkss = $collBookmarkss;
                $this->collBookmarkssPartial = false;
            }
        }

        return $this->collBookmarkss;
    }

    /**
     * Sets a collection of Bookmarks objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $bookmarkss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEpisodes The current object (for fluent API support)
     */
    public function setBookmarkss(Collection $bookmarkss, ConnectionInterface $con = null)
    {
        /** @var Bookmarks[] $bookmarkssToDelete */
        $bookmarkssToDelete = $this->getBookmarkss(new Criteria(), $con)->diff($bookmarkss);


        $this->bookmarkssScheduledForDeletion = $bookmarkssToDelete;

        foreach ($bookmarkssToDelete as $bookmarksRemoved) {
            $bookmarksRemoved->setEpisodes(null);
        }

        $this->collBookmarkss = null;
        foreach ($bookmarkss as $bookmarks) {
            $this->addBookmarks($bookmarks);
        }

        $this->collBookmarkss = $bookmarkss;
        $this->collBookmarkssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseBookmarks objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseBookmarks objects.
     * @throws PropelException
     */
    public function countBookmarkss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collBookmarkssPartial && !$this->isNew();
        if (null === $this->collBookmarkss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBookmarkss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getBookmarkss());
            }

            $query = BookmarksQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEpisodes($this)
                ->count($con);
        }

        return count($this->collBookmarkss);
    }

    /**
     * Method called to associate a Bookmarks object to this object
     * through the Bookmarks foreign key attribute.
     *
     * @param  Bookmarks $l Bookmarks
     * @return $this|\Models\Episodes\Episodes The current object (for fluent API support)
     */
    public function addBookmarks(Bookmarks $l)
    {
        if ($this->collBookmarkss === null) {
            $this->initBookmarkss();
            $this->collBookmarkssPartial = true;
        }

        if (!$this->collBookmarkss->contains($l)) {
            $this->doAddBookmarks($l);

            if ($this->bookmarkssScheduledForDeletion and $this->bookmarkssScheduledForDeletion->contains($l)) {
                $this->bookmarkssScheduledForDeletion->remove($this->bookmarkssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Bookmarks $bookmarks The Bookmarks object to add.
     */
    protected function doAddBookmarks(Bookmarks $bookmarks)
    {
        $this->collBookmarkss[]= $bookmarks;
        $bookmarks->setEpisodes($this);
    }

    /**
     * @param  Bookmarks $bookmarks The Bookmarks object to remove.
     * @return $this|ChildEpisodes The current object (for fluent API support)
     */
    public function removeBookmarks(Bookmarks $bookmarks)
    {
        if ($this->getBookmarkss()->contains($bookmarks)) {
            $pos = $this->collBookmarkss->search($bookmarks);
            $this->collBookmarkss->remove($pos);
            if (null === $this->bookmarkssScheduledForDeletion) {
                $this->bookmarkssScheduledForDeletion = clone $this->collBookmarkss;
                $this->bookmarkssScheduledForDeletion->clear();
            }
            $this->bookmarkssScheduledForDeletion[]= clone $bookmarks;
            $bookmarks->setEpisodes(null);
        }

        return $this;
    }

    /**
     * Clears out the collUserEpisodeTagss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserEpisodeTagss()
     */
    public function clearUserEpisodeTagss()
    {
        $this->collUserEpisodeTagss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserEpisodeTagss collection loaded partially.
     */
    public function resetPartialUserEpisodeTagss($v = true)
    {
        $this->collUserEpisodeTagssPartial = $v;
    }

    /**
     * Initializes the collUserEpisodeTagss collection.
     *
     * By default this just sets the collUserEpisodeTagss collection to an empty array (like clearcollUserEpisodeTagss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserEpisodeTagss($overrideExisting = true)
    {
        if (null !== $this->collUserEpisodeTagss && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserEpisodeTagsTableMap::getTableMap()->getCollectionClassName();

        $this->collUserEpisodeTagss = new $collectionClassName;
        $this->collUserEpisodeTagss->setModel('\Models\UserTags\UserEpisodeTags');
    }

    /**
     * Gets an array of UserEpisodeTags objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEpisodes is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|UserEpisodeTags[] List of UserEpisodeTags objects
     * @throws PropelException
     */
    public function getUserEpisodeTagss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserEpisodeTagssPartial && !$this->isNew();
        if (null === $this->collUserEpisodeTagss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserEpisodeTagss) {
                // return empty collection
                $this->initUserEpisodeTagss();
            } else {
                $collUserEpisodeTagss = UserEpisodeTagsQuery::create(null, $criteria)
                    ->filterByEpisodesTags($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserEpisodeTagssPartial && count($collUserEpisodeTagss)) {
                        $this->initUserEpisodeTagss(false);

                        foreach ($collUserEpisodeTagss as $obj) {
                            if (false == $this->collUserEpisodeTagss->contains($obj)) {
                                $this->collUserEpisodeTagss->append($obj);
                            }
                        }

                        $this->collUserEpisodeTagssPartial = true;
                    }

                    return $collUserEpisodeTagss;
                }

                if ($partial && $this->collUserEpisodeTagss) {
                    foreach ($this->collUserEpisodeTagss as $obj) {
                        if ($obj->isNew()) {
                            $collUserEpisodeTagss[] = $obj;
                        }
                    }
                }

                $this->collUserEpisodeTagss = $collUserEpisodeTagss;
                $this->collUserEpisodeTagssPartial = false;
            }
        }

        return $this->collUserEpisodeTagss;
    }

    /**
     * Sets a collection of UserEpisodeTags objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userEpisodeTagss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEpisodes The current object (for fluent API support)
     */
    public function setUserEpisodeTagss(Collection $userEpisodeTagss, ConnectionInterface $con = null)
    {
        /** @var UserEpisodeTags[] $userEpisodeTagssToDelete */
        $userEpisodeTagssToDelete = $this->getUserEpisodeTagss(new Criteria(), $con)->diff($userEpisodeTagss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userEpisodeTagssScheduledForDeletion = clone $userEpisodeTagssToDelete;

        foreach ($userEpisodeTagssToDelete as $userEpisodeTagsRemoved) {
            $userEpisodeTagsRemoved->setEpisodesTags(null);
        }

        $this->collUserEpisodeTagss = null;
        foreach ($userEpisodeTagss as $userEpisodeTags) {
            $this->addUserEpisodeTags($userEpisodeTags);
        }

        $this->collUserEpisodeTagss = $userEpisodeTagss;
        $this->collUserEpisodeTagssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseUserEpisodeTags objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseUserEpisodeTags objects.
     * @throws PropelException
     */
    public function countUserEpisodeTagss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserEpisodeTagssPartial && !$this->isNew();
        if (null === $this->collUserEpisodeTagss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserEpisodeTagss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserEpisodeTagss());
            }

            $query = UserEpisodeTagsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEpisodesTags($this)
                ->count($con);
        }

        return count($this->collUserEpisodeTagss);
    }

    /**
     * Method called to associate a UserEpisodeTags object to this object
     * through the UserEpisodeTags foreign key attribute.
     *
     * @param  UserEpisodeTags $l UserEpisodeTags
     * @return $this|\Models\Episodes\Episodes The current object (for fluent API support)
     */
    public function addUserEpisodeTags(UserEpisodeTags $l)
    {
        if ($this->collUserEpisodeTagss === null) {
            $this->initUserEpisodeTagss();
            $this->collUserEpisodeTagssPartial = true;
        }

        if (!$this->collUserEpisodeTagss->contains($l)) {
            $this->doAddUserEpisodeTags($l);

            if ($this->userEpisodeTagssScheduledForDeletion and $this->userEpisodeTagssScheduledForDeletion->contains($l)) {
                $this->userEpisodeTagssScheduledForDeletion->remove($this->userEpisodeTagssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param UserEpisodeTags $userEpisodeTags The UserEpisodeTags object to add.
     */
    protected function doAddUserEpisodeTags(UserEpisodeTags $userEpisodeTags)
    {
        $this->collUserEpisodeTagss[]= $userEpisodeTags;
        $userEpisodeTags->setEpisodesTags($this);
    }

    /**
     * @param  UserEpisodeTags $userEpisodeTags The UserEpisodeTags object to remove.
     * @return $this|ChildEpisodes The current object (for fluent API support)
     */
    public function removeUserEpisodeTags(UserEpisodeTags $userEpisodeTags)
    {
        if ($this->getUserEpisodeTagss()->contains($userEpisodeTags)) {
            $pos = $this->collUserEpisodeTagss->search($userEpisodeTags);
            $this->collUserEpisodeTagss->remove($pos);
            if (null === $this->userEpisodeTagssScheduledForDeletion) {
                $this->userEpisodeTagssScheduledForDeletion = clone $this->collUserEpisodeTagss;
                $this->userEpisodeTagssScheduledForDeletion->clear();
            }
            $this->userEpisodeTagssScheduledForDeletion[]= clone $userEpisodeTags;
            $userEpisodeTags->setEpisodesTags(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Episodes is new, it will return
     * an empty collection; or if this Episodes has previously
     * been saved, it will retrieve related UserEpisodeTagss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Episodes.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|UserEpisodeTags[] List of UserEpisodeTags objects
     */
    public function getUserEpisodeTagssJoinEpisodeTag(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = UserEpisodeTagsQuery::create(null, $criteria);
        $query->joinWith('EpisodeTag', $joinBehavior);

        return $this->getUserEpisodeTagss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Episodes is new, it will return
     * an empty collection; or if this Episodes has previously
     * been saved, it will retrieve related UserEpisodeTagss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Episodes.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|UserEpisodeTags[] List of UserEpisodeTags objects
     */
    public function getUserEpisodeTagssJoinUsersEpisodeTags(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = UserEpisodeTagsQuery::create(null, $criteria);
        $query->joinWith('UsersEpisodeTags', $joinBehavior);

        return $this->getUserEpisodeTagss($query, $con);
    }

    /**
     * Clears out the collPlaylists collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlaylists()
     */
    public function clearPlaylists()
    {
        $this->collPlaylists = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collPlaylists crossRef collection.
     *
     * By default this just sets the collPlaylists collection to an empty collection (like clearPlaylists());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPlaylists()
    {
        $collectionClassName = PlaylistEpisodesTableMap::getTableMap()->getCollectionClassName();

        $this->collPlaylists = new $collectionClassName;
        $this->collPlaylistsPartial = true;
        $this->collPlaylists->setModel('\Models\Playlists\Playlists');
    }

    /**
     * Checks if the collPlaylists collection is loaded.
     *
     * @return bool
     */
    public function isPlaylistsLoaded()
    {
        return null !== $this->collPlaylists;
    }

    /**
     * Gets a collection of Playlists objects related by a many-to-many relationship
     * to the current object by way of the playlist_episodes cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEpisodes is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|Playlists[] List of Playlists objects
     */
    public function getPlaylists(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistsPartial && !$this->isNew();
        if (null === $this->collPlaylists || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPlaylists) {
                    $this->initPlaylists();
                }
            } else {

                $query = PlaylistsQuery::create(null, $criteria)
                    ->filterByEpisode($this);
                $collPlaylists = $query->find($con);
                if (null !== $criteria) {
                    return $collPlaylists;
                }

                if ($partial && $this->collPlaylists) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collPlaylists as $obj) {
                        if (!$collPlaylists->contains($obj)) {
                            $collPlaylists[] = $obj;
                        }
                    }
                }

                $this->collPlaylists = $collPlaylists;
                $this->collPlaylistsPartial = false;
            }
        }

        return $this->collPlaylists;
    }

    /**
     * Sets a collection of Playlists objects related by a many-to-many relationship
     * to the current object by way of the playlist_episodes cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $playlists A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildEpisodes The current object (for fluent API support)
     */
    public function setPlaylists(Collection $playlists, ConnectionInterface $con = null)
    {
        $this->clearPlaylists();
        $currentPlaylists = $this->getPlaylists();

        $playlistsScheduledForDeletion = $currentPlaylists->diff($playlists);

        foreach ($playlistsScheduledForDeletion as $toDelete) {
            $this->removePlaylist($toDelete);
        }

        foreach ($playlists as $playlist) {
            if (!$currentPlaylists->contains($playlist)) {
                $this->doAddPlaylist($playlist);
            }
        }

        $this->collPlaylistsPartial = false;
        $this->collPlaylists = $playlists;

        return $this;
    }

    /**
     * Gets the number of Playlists objects related by a many-to-many relationship
     * to the current object by way of the playlist_episodes cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Playlists objects
     */
    public function countPlaylists(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistsPartial && !$this->isNew();
        if (null === $this->collPlaylists || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlaylists) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getPlaylists());
                }

                $query = PlaylistsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByEpisode($this)
                    ->count($con);
            }
        } else {
            return count($this->collPlaylists);
        }
    }

    /**
     * Associate a Playlists to this object
     * through the playlist_episodes cross reference table.
     *
     * @param Playlists $playlist
     * @return ChildEpisodes The current object (for fluent API support)
     */
    public function addPlaylist(Playlists $playlist)
    {
        if ($this->collPlaylists === null) {
            $this->initPlaylists();
        }

        if (!$this->getPlaylists()->contains($playlist)) {
            // only add it if the **same** object is not already associated
            $this->collPlaylists->push($playlist);
            $this->doAddPlaylist($playlist);
        }

        return $this;
    }

    /**
     *
     * @param Playlists $playlist
     */
    protected function doAddPlaylist(Playlists $playlist)
    {
        $playlistEpisodes = new ChildPlaylistEpisodes();

        $playlistEpisodes->setPlaylist($playlist);

        $playlistEpisodes->setEpisode($this);

        $this->addPlaylistEpisodes($playlistEpisodes);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$playlist->isEpisodesLoaded()) {
            $playlist->initEpisodes();
            $playlist->getEpisodes()->push($this);
        } elseif (!$playlist->getEpisodes()->contains($this)) {
            $playlist->getEpisodes()->push($this);
        }

    }

    /**
     * Remove playlist of this object
     * through the playlist_episodes cross reference table.
     *
     * @param Playlists $playlist
     * @return ChildEpisodes The current object (for fluent API support)
     */
    public function removePlaylist(Playlists $playlist)
    {
        if ($this->getPlaylists()->contains($playlist)) {
            $playlistEpisodes = new ChildPlaylistEpisodes();
            $playlistEpisodes->setPlaylist($playlist);
            if ($playlist->isEpisodesLoaded()) {
                //remove the back reference if available
                $playlist->getEpisodes()->removeObject($this);
            }

            $playlistEpisodes->setEpisode($this);
            $this->removePlaylistEpisodes(clone $playlistEpisodes);
            $playlistEpisodes->clear();

            $this->collPlaylists->remove($this->collPlaylists->search($playlist));

            if (null === $this->playlistsScheduledForDeletion) {
                $this->playlistsScheduledForDeletion = clone $this->collPlaylists;
                $this->playlistsScheduledForDeletion->clear();
            }

            $this->playlistsScheduledForDeletion->push($playlist);
        }


        return $this;
    }

    /**
     * Clears out the collEpisodeTagUsersEpisodeTagss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEpisodeTagUsersEpisodeTagss()
     */
    public function clearEpisodeTagUsersEpisodeTagss()
    {
        $this->collEpisodeTagUsersEpisodeTagss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the combinationCollEpisodeTagUsersEpisodeTagss crossRef collection.
     *
     * By default this just sets the combinationCollEpisodeTagUsersEpisodeTagss collection to an empty collection (like clearEpisodeTagUsersEpisodeTagss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initEpisodeTagUsersEpisodeTagss()
    {
        $this->combinationCollEpisodeTagUsersEpisodeTagss = new ObjectCombinationCollection;
        $this->combinationCollEpisodeTagUsersEpisodeTagssPartial = true;
    }

    /**
     * Checks if the combinationCollEpisodeTagUsersEpisodeTagss collection is loaded.
     *
     * @return bool
     */
    public function isEpisodeTagUsersEpisodeTagssLoaded()
    {
        return null !== $this->combinationCollEpisodeTagUsersEpisodeTagss;
    }

    /**
     * Gets a combined collection of Tags, Users objects related by a many-to-many relationship
     * to the current object by way of the user_episode_tags cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEpisodes is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCombinationCollection Combination list of Tags, Users objects
     */
    public function getEpisodeTagUsersEpisodeTagss($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollEpisodeTagUsersEpisodeTagssPartial && !$this->isNew();
        if (null === $this->combinationCollEpisodeTagUsersEpisodeTagss || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->combinationCollEpisodeTagUsersEpisodeTagss) {
                    $this->initEpisodeTagUsersEpisodeTagss();
                }
            } else {

                $query = UserEpisodeTagsQuery::create(null, $criteria)
                    ->filterByEpisodesTags($this)
                    ->joinEpisodeTag()
                    ->joinUsersEpisodeTags()
                ;

                $items = $query->find($con);
                $combinationCollEpisodeTagUsersEpisodeTagss = new ObjectCombinationCollection();
                foreach ($items as $item) {
                    $combination = [];

                    $combination[] = $item->getEpisodeTag();
                    $combination[] = $item->getUsersEpisodeTags();
                    $combinationCollEpisodeTagUsersEpisodeTagss[] = $combination;
                }

                if (null !== $criteria) {
                    return $combinationCollEpisodeTagUsersEpisodeTagss;
                }

                if ($partial && $this->combinationCollEpisodeTagUsersEpisodeTagss) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->combinationCollEpisodeTagUsersEpisodeTagss as $obj) {
                        if (!call_user_func_array([$combinationCollEpisodeTagUsersEpisodeTagss, 'contains'], $obj)) {
                            $combinationCollEpisodeTagUsersEpisodeTagss[] = $obj;
                        }
                    }
                }

                $this->combinationCollEpisodeTagUsersEpisodeTagss = $combinationCollEpisodeTagUsersEpisodeTagss;
                $this->combinationCollEpisodeTagUsersEpisodeTagssPartial = false;
            }
        }

        return $this->combinationCollEpisodeTagUsersEpisodeTagss;
    }

    /**
     * Returns a not cached ObjectCollection of Tags objects. This will hit always the databases.
     * If you have attached new Tags object to this object you need to call `save` first to get
     * the correct return value. Use getEpisodeTagUsersEpisodeTagss() to get the current internal state.
     *
     * @param Users $usersEpisodeTags
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return Tags[]|ObjectCollection
     */
    public function getEpisodeTags(Users $usersEpisodeTags = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createEpisodeTagsQuery($usersEpisodeTags, $criteria)->find($con);
    }

    /**
     * Sets a collection of ChildTags, ChildUsers combination objects related by a many-to-many relationship
     * to the current object by way of the user_episode_tags cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $episodeTagUsersEpisodeTagss A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildEpisodes The current object (for fluent API support)
     */
    public function setEpisodeTagUsersEpisodeTagss(Collection $episodeTagUsersEpisodeTagss, ConnectionInterface $con = null)
    {
        $this->clearEpisodeTagUsersEpisodeTagss();
        $currentEpisodeTagUsersEpisodeTagss = $this->getEpisodeTagUsersEpisodeTagss();

        $combinationCollEpisodeTagUsersEpisodeTagssScheduledForDeletion = $currentEpisodeTagUsersEpisodeTagss->diff($episodeTagUsersEpisodeTagss);

        foreach ($combinationCollEpisodeTagUsersEpisodeTagssScheduledForDeletion as $toDelete) {
            call_user_func_array([$this, 'removeEpisodeTagUsersEpisodeTags'], $toDelete);
        }

        foreach ($episodeTagUsersEpisodeTagss as $episodeTagUsersEpisodeTags) {
            if (!call_user_func_array([$currentEpisodeTagUsersEpisodeTagss, 'contains'], $episodeTagUsersEpisodeTags)) {
                call_user_func_array([$this, 'doAddEpisodeTagUsersEpisodeTags'], $episodeTagUsersEpisodeTags);
            }
        }

        $this->combinationCollEpisodeTagUsersEpisodeTagssPartial = false;
        $this->combinationCollEpisodeTagUsersEpisodeTagss = $episodeTagUsersEpisodeTagss;

        return $this;
    }

    /**
     * Gets the number of ChildTags, ChildUsers combination objects related by a many-to-many relationship
     * to the current object by way of the user_episode_tags cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildTags, ChildUsers combination objects
     */
    public function countEpisodeTagUsersEpisodeTagss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollEpisodeTagUsersEpisodeTagssPartial && !$this->isNew();
        if (null === $this->combinationCollEpisodeTagUsersEpisodeTagss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->combinationCollEpisodeTagUsersEpisodeTagss) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getEpisodeTagUsersEpisodeTagss());
                }

                $query = UserEpisodeTagsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByEpisodesTags($this)
                    ->count($con);
            }
        } else {
            return count($this->combinationCollEpisodeTagUsersEpisodeTagss);
        }
    }

    /**
     * Returns the not cached count of Tags objects. This will hit always the databases.
     * If you have attached new Tags object to this object you need to call `save` first to get
     * the correct return value. Use getEpisodeTagUsersEpisodeTagss() to get the current internal state.
     *
     * @param Users $usersEpisodeTags
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return integer
     */
    public function countEpisodeTags(Users $usersEpisodeTags = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createEpisodeTagsQuery($usersEpisodeTags, $criteria)->count($con);
    }

    /**
     * Associate a Tags to this object
     * through the user_episode_tags cross reference table.
     *
     * @param Tags $episodeTag,
     * @param Users $usersEpisodeTags
     * @return ChildEpisodes The current object (for fluent API support)
     */
    public function addEpisodeTag(Tags $episodeTag, Users $usersEpisodeTags)
    {
        if ($this->combinationCollEpisodeTagUsersEpisodeTagss === null) {
            $this->initEpisodeTagUsersEpisodeTagss();
        }

        if (!$this->getEpisodeTagUsersEpisodeTagss()->contains($episodeTag, $usersEpisodeTags)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollEpisodeTagUsersEpisodeTagss->push($episodeTag, $usersEpisodeTags);
            $this->doAddEpisodeTagUsersEpisodeTags($episodeTag, $usersEpisodeTags);
        }

        return $this;
    }

    /**
     * Associate a Users to this object
     * through the user_episode_tags cross reference table.
     *
     * @param Users $usersEpisodeTags,
     * @param Tags $episodeTag
     * @return ChildEpisodes The current object (for fluent API support)
     */
    public function addUsersEpisodeTags(Users $usersEpisodeTags, Tags $episodeTag)
    {
        if ($this->combinationCollEpisodeTagUsersEpisodeTagss === null) {
            $this->initEpisodeTagUsersEpisodeTagss();
        }

        if (!$this->getEpisodeTagUsersEpisodeTagss()->contains($usersEpisodeTags, $episodeTag)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollEpisodeTagUsersEpisodeTagss->push($usersEpisodeTags, $episodeTag);
            $this->doAddEpisodeTagUsersEpisodeTags($usersEpisodeTags, $episodeTag);
        }

        return $this;
    }

    /**
     *
     * @param Tags $episodeTag,
     * @param Users $usersEpisodeTags
     */
    protected function doAddEpisodeTagUsersEpisodeTags(Tags $episodeTag, Users $usersEpisodeTags)
    {
        $userEpisodeTags = new UserEpisodeTags();

        $userEpisodeTags->setEpisodeTag($episodeTag);
        $userEpisodeTags->setUsersEpisodeTags($usersEpisodeTags);

        $userEpisodeTags->setEpisodesTags($this);

        $this->addUserEpisodeTags($userEpisodeTags);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($episodeTag->isEpisodesTagsUsersEpisodeTagssLoaded()) {
            $episodeTag->initEpisodesTagsUsersEpisodeTagss();
            $episodeTag->getEpisodesTagsUsersEpisodeTagss()->push($this, $usersEpisodeTags);
        } elseif (!$episodeTag->getEpisodesTagsUsersEpisodeTagss()->contains($this, $usersEpisodeTags)) {
            $episodeTag->getEpisodesTagsUsersEpisodeTagss()->push($this, $usersEpisodeTags);
        }

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($usersEpisodeTags->isEpisodesTagsEpisodeTagsLoaded()) {
            $usersEpisodeTags->initEpisodesTagsEpisodeTags();
            $usersEpisodeTags->getEpisodesTagsEpisodeTags()->push($this, $episodeTag);
        } elseif (!$usersEpisodeTags->getEpisodesTagsEpisodeTags()->contains($this, $episodeTag)) {
            $usersEpisodeTags->getEpisodesTagsEpisodeTags()->push($this, $episodeTag);
        }

    }

    /**
     * Remove episodeTag, usersEpisodeTags of this object
     * through the user_episode_tags cross reference table.
     *
     * @param Tags $episodeTag,
     * @param Users $usersEpisodeTags
     * @return ChildEpisodes The current object (for fluent API support)
     */
    public function removeEpisodeTagUsersEpisodeTags(Tags $episodeTag, Users $usersEpisodeTags)
    {
        if ($this->getEpisodeTagUsersEpisodeTagss()->contains($episodeTag, $usersEpisodeTags)) {
            $userEpisodeTags = new UserEpisodeTags();
            $userEpisodeTags->setEpisodeTag($episodeTag);
            if ($episodeTag->isEpisodesTagsUsersEpisodeTagssLoaded()) {
                //remove the back reference if available
                $episodeTag->getEpisodesTagsUsersEpisodeTagss()->removeObject($this, $usersEpisodeTags);
            }

            $userEpisodeTags->setUsersEpisodeTags($usersEpisodeTags);
            if ($usersEpisodeTags->isEpisodesTagsEpisodeTagsLoaded()) {
                //remove the back reference if available
                $usersEpisodeTags->getEpisodesTagsEpisodeTags()->removeObject($this, $episodeTag);
            }

            $userEpisodeTags->setEpisodesTags($this);
            $this->removeUserEpisodeTags(clone $userEpisodeTags);
            $userEpisodeTags->clear();

            $this->combinationCollEpisodeTagUsersEpisodeTagss->remove($this->combinationCollEpisodeTagUsersEpisodeTagss->search($episodeTag, $usersEpisodeTags));

            if (null === $this->combinationCollEpisodeTagUsersEpisodeTagssScheduledForDeletion) {
                $this->combinationCollEpisodeTagUsersEpisodeTagssScheduledForDeletion = clone $this->combinationCollEpisodeTagUsersEpisodeTagss;
                $this->combinationCollEpisodeTagUsersEpisodeTagssScheduledForDeletion->clear();
            }

            $this->combinationCollEpisodeTagUsersEpisodeTagssScheduledForDeletion->push($episodeTag, $usersEpisodeTags);
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
        if (null !== $this->aPodcasts) {
            $this->aPodcasts->removeEpisodes($this);
        }
        $this->id = null;
        $this->episode_id = null;
        $this->podcast_id = null;
        $this->number_of_plays = null;
        $this->number_of_downloads = null;
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
            if ($this->collUserEpisodess) {
                foreach ($this->collUserEpisodess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlaylistEpisodess) {
                foreach ($this->collPlaylistEpisodess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collBookmarkss) {
                foreach ($this->collBookmarkss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserEpisodeTagss) {
                foreach ($this->collUserEpisodeTagss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlaylists) {
                foreach ($this->collPlaylists as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->combinationCollEpisodeTagUsersEpisodeTagss) {
                foreach ($this->combinationCollEpisodeTagUsersEpisodeTagss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collUserEpisodess = null;
        $this->collPlaylistEpisodess = null;
        $this->collBookmarkss = null;
        $this->collUserEpisodeTagss = null;
        $this->collPlaylists = null;
        $this->combinationCollEpisodeTagUsersEpisodeTagss = null;
        $this->aPodcasts = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EpisodesTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildEpisodes The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[EpisodesTableMap::COL_UPDATED_AT] = true;

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
