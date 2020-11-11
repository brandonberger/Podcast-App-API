<?php

namespace Models\Users\Base;

use \Exception;
use \PDO;
use Models\AudioPlayerSettings\AudioPlayerSettings;
use Models\AudioPlayerSettings\AudioPlayerSettingsQuery;
use Models\AudioPlayerSettings\Base\AudioPlayerSettings as BaseAudioPlayerSettings;
use Models\AudioPlayerSettings\Map\AudioPlayerSettingsTableMap;
use Models\Episodes\Episodes;
use Models\Episodes\UserEpisodes;
use Models\Episodes\UserEpisodesQuery;
use Models\Episodes\Base\UserEpisodes as BaseUserEpisodes;
use Models\Episodes\Map\UserEpisodesTableMap;
use Models\Logging\Logging;
use Models\Logging\LoggingQuery;
use Models\Logging\Base\Logging as BaseLogging;
use Models\Logging\Map\LoggingTableMap;
use Models\Playlists\PlaylistComments;
use Models\Playlists\PlaylistCommentsQuery;
use Models\Playlists\Playlists;
use Models\Playlists\PlaylistsQuery;
use Models\Playlists\UserPlaylists;
use Models\Playlists\UserPlaylistsQuery;
use Models\Playlists\Base\PlaylistComments as BasePlaylistComments;
use Models\Playlists\Base\UserPlaylists as BaseUserPlaylists;
use Models\Playlists\Map\PlaylistCommentsTableMap;
use Models\Playlists\Map\UserPlaylistsTableMap;
use Models\Podcasts\Podcasts;
use Models\Podcasts\PodcastsQuery;
use Models\Tags\Tags;
use Models\UserPodcasts\UserPodcasts;
use Models\UserPodcasts\UserPodcastsQuery;
use Models\UserPodcasts\Base\UserPodcasts as BaseUserPodcasts;
use Models\UserPodcasts\Map\UserPodcastsTableMap;
use Models\UserSettings\UserSettings;
use Models\UserSettings\UserSettingsQuery;
use Models\UserSettings\Base\UserSettings as BaseUserSettings;
use Models\UserSettings\Map\UserSettingsTableMap;
use Models\UserTags\UserEpisodeTags;
use Models\UserTags\UserEpisodeTagsQuery;
use Models\UserTags\UserPlaylistTags;
use Models\UserTags\UserPlaylistTagsQuery;
use Models\UserTags\Base\UserEpisodeTags as BaseUserEpisodeTags;
use Models\UserTags\Base\UserPlaylistTags as BaseUserPlaylistTags;
use Models\UserTags\Map\UserEpisodeTagsTableMap;
use Models\UserTags\Map\UserPlaylistTagsTableMap;
use Models\Users\UserRelations as ChildUserRelations;
use Models\Users\UserRelationsQuery as ChildUserRelationsQuery;
use Models\Users\UserSessions as ChildUserSessions;
use Models\Users\UserSessionsQuery as ChildUserSessionsQuery;
use Models\Users\Users as ChildUsers;
use Models\Users\UsersQuery as ChildUsersQuery;
use Models\Users\Map\UserRelationsTableMap;
use Models\Users\Map\UserSessionsTableMap;
use Models\Users\Map\UsersTableMap;
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

/**
 * Base class that represents a row from the 'users' table.
 *
 *
 *
 * @package    propel.generator.Models.Users.Base
 */
abstract class Users implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Users\\Map\\UsersTableMap';


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
     * The value for the email field.
     *
     * @var        string
     */
    protected $email;

    /**
     * The value for the first_name field.
     *
     * @var        string
     */
    protected $first_name;

    /**
     * The value for the last_name field.
     *
     * @var        string
     */
    protected $last_name;

    /**
     * The value for the active field.
     *
     * Note: this column has a database default value of: 1
     * @var        int
     */
    protected $active;

    /**
     * The value for the google_id field.
     *
     * @var        string
     */
    protected $google_id;

    /**
     * The value for the image_url field.
     *
     * @var        string
     */
    protected $image_url;

    /**
     * @var        ObjectCollection|ChildUserSessions[] Collection to store aggregation of ChildUserSessions objects.
     */
    protected $collUserSessionss;
    protected $collUserSessionssPartial;

    /**
     * @var        ObjectCollection|ChildUserRelations[] Collection to store aggregation of ChildUserRelations objects.
     */
    protected $collUserRelationssRelatedByFollowerId;
    protected $collUserRelationssRelatedByFollowerIdPartial;

    /**
     * @var        ObjectCollection|ChildUserRelations[] Collection to store aggregation of ChildUserRelations objects.
     */
    protected $collUserRelationssRelatedByFollowingId;
    protected $collUserRelationssRelatedByFollowingIdPartial;

    /**
     * @var        ObjectCollection|AudioPlayerSettings[] Collection to store aggregation of AudioPlayerSettings objects.
     */
    protected $collAudioPlayerSettingss;
    protected $collAudioPlayerSettingssPartial;

    /**
     * @var        ObjectCollection|UserSettings[] Collection to store aggregation of UserSettings objects.
     */
    protected $collUserSettingss;
    protected $collUserSettingssPartial;

    /**
     * @var        ObjectCollection|UserPodcasts[] Collection to store aggregation of UserPodcasts objects.
     */
    protected $collUserPodcastss;
    protected $collUserPodcastssPartial;

    /**
     * @var        ObjectCollection|UserPlaylists[] Collection to store aggregation of UserPlaylists objects.
     */
    protected $collUserPlaylistss;
    protected $collUserPlaylistssPartial;

    /**
     * @var        ObjectCollection|PlaylistComments[] Collection to store aggregation of PlaylistComments objects.
     */
    protected $collPlaylistCommentss;
    protected $collPlaylistCommentssPartial;

    /**
     * @var        ObjectCollection|UserEpisodes[] Collection to store aggregation of UserEpisodes objects.
     */
    protected $collUserEpisodess;
    protected $collUserEpisodessPartial;

    /**
     * @var        ObjectCollection|UserPlaylistTags[] Collection to store aggregation of UserPlaylistTags objects.
     */
    protected $collUserPlaylistTagss;
    protected $collUserPlaylistTagssPartial;

    /**
     * @var        ObjectCollection|UserEpisodeTags[] Collection to store aggregation of UserEpisodeTags objects.
     */
    protected $collUserEpisodeTagss;
    protected $collUserEpisodeTagssPartial;

    /**
     * @var        ObjectCollection|Logging[] Collection to store aggregation of Logging objects.
     */
    protected $collLoggings;
    protected $collLoggingsPartial;

    /**
     * @var        ObjectCollection|ChildUsers[] Cross Collection to store aggregation of ChildUsers objects.
     */
    protected $collFollowings;

    /**
     * @var bool
     */
    protected $collFollowingsPartial;

    /**
     * @var        ObjectCollection|ChildUsers[] Cross Collection to store aggregation of ChildUsers objects.
     */
    protected $collFollowers;

    /**
     * @var bool
     */
    protected $collFollowersPartial;

    /**
     * @var        ObjectCollection|Podcasts[] Cross Collection to store aggregation of Podcasts objects.
     */
    protected $collPodcastss;

    /**
     * @var bool
     */
    protected $collPodcastssPartial;

    /**
     * @var        ObjectCollection|Playlists[] Cross Collection to store aggregation of Playlists objects.
     */
    protected $collPlaylistss;

    /**
     * @var bool
     */
    protected $collPlaylistssPartial;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildPlaylists, ChildTags combination combinations.
     */
    protected $combinationCollPlaylistsTagsPlaylistTags;

    /**
     * @var bool
     */
    protected $combinationCollPlaylistsTagsPlaylistTagsPartial;

    /**
     * @var        ObjectCollection|Playlists[] Cross Collection to store aggregation of Playlists objects.
     */
    protected $collPlaylistsTagss;

    /**
     * @var bool
     */
    protected $collPlaylistsTagssPartial;

    /**
     * @var        ObjectCollection|Tags[] Cross Collection to store aggregation of Tags objects.
     */
    protected $collPlaylistTags;

    /**
     * @var bool
     */
    protected $collPlaylistTagsPartial;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildEpisodes, ChildTags combination combinations.
     */
    protected $combinationCollEpisodesTagsEpisodeTags;

    /**
     * @var bool
     */
    protected $combinationCollEpisodesTagsEpisodeTagsPartial;

    /**
     * @var        ObjectCollection|Episodes[] Cross Collection to store aggregation of Episodes objects.
     */
    protected $collEpisodesTagss;

    /**
     * @var bool
     */
    protected $collEpisodesTagssPartial;

    /**
     * @var        ObjectCollection|Tags[] Cross Collection to store aggregation of Tags objects.
     */
    protected $collEpisodeTags;

    /**
     * @var bool
     */
    protected $collEpisodeTagsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUsers[]
     */
    protected $followingsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUsers[]
     */
    protected $followersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Podcasts[]
     */
    protected $podcastssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Playlists[]
     */
    protected $playlistssScheduledForDeletion = null;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildPlaylists, ChildTags combination combinations.
     */
    protected $combinationCollPlaylistsTagsPlaylistTagsScheduledForDeletion = null;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildEpisodes, ChildTags combination combinations.
     */
    protected $combinationCollEpisodesTagsEpisodeTagsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserSessions[]
     */
    protected $userSessionssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserRelations[]
     */
    protected $userRelationssRelatedByFollowerIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserRelations[]
     */
    protected $userRelationssRelatedByFollowingIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|AudioPlayerSettings[]
     */
    protected $audioPlayerSettingssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|UserSettings[]
     */
    protected $userSettingssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|UserPodcasts[]
     */
    protected $userPodcastssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|UserPlaylists[]
     */
    protected $userPlaylistssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|PlaylistComments[]
     */
    protected $playlistCommentssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|UserEpisodes[]
     */
    protected $userEpisodessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|UserPlaylistTags[]
     */
    protected $userPlaylistTagssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|UserEpisodeTags[]
     */
    protected $userEpisodeTagssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Logging[]
     */
    protected $loggingsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->active = 1;
    }

    /**
     * Initializes internal state of Models\Users\Base\Users object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
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
     * Compares this with another <code>Users</code> instance.  If
     * <code>obj</code> is an instance of <code>Users</code>, delegates to
     * <code>equals(Users)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Users The current object, for fluid interface
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
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [first_name] column value.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Get the [last_name] column value.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Get the [active] column value.
     *
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Get the [google_id] column value.
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Get the [image_url] column value.
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->image_url;
    }

    /**
     * Set the value of [id] column.
     *
     * @param string $v new value
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UsersTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UsersTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [first_name] column.
     *
     * @param string $v new value
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name !== $v) {
            $this->first_name = $v;
            $this->modifiedColumns[UsersTableMap::COL_FIRST_NAME] = true;
        }

        return $this;
    } // setFirstName()

    /**
     * Set the value of [last_name] column.
     *
     * @param string $v new value
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name !== $v) {
            $this->last_name = $v;
            $this->modifiedColumns[UsersTableMap::COL_LAST_NAME] = true;
        }

        return $this;
    } // setLastName()

    /**
     * Set the value of [active] column.
     *
     * @param int $v new value
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function setActive($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->active !== $v) {
            $this->active = $v;
            $this->modifiedColumns[UsersTableMap::COL_ACTIVE] = true;
        }

        return $this;
    } // setActive()

    /**
     * Set the value of [google_id] column.
     *
     * @param string $v new value
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function setGoogleId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->google_id !== $v) {
            $this->google_id = $v;
            $this->modifiedColumns[UsersTableMap::COL_GOOGLE_ID] = true;
        }

        return $this;
    } // setGoogleId()

    /**
     * Set the value of [image_url] column.
     *
     * @param string $v new value
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function setImageUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->image_url !== $v) {
            $this->image_url = $v;
            $this->modifiedColumns[UsersTableMap::COL_IMAGE_URL] = true;
        }

        return $this;
    } // setImageUrl()

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
            if ($this->active !== 1) {
                return false;
            }

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UsersTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UsersTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UsersTableMap::translateFieldName('FirstName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->first_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UsersTableMap::translateFieldName('LastName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->last_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UsersTableMap::translateFieldName('Active', TableMap::TYPE_PHPNAME, $indexType)];
            $this->active = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UsersTableMap::translateFieldName('GoogleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->google_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UsersTableMap::translateFieldName('ImageUrl', TableMap::TYPE_PHPNAME, $indexType)];
            $this->image_url = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = UsersTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Users\\Users'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(UsersTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUsersQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collUserSessionss = null;

            $this->collUserRelationssRelatedByFollowerId = null;

            $this->collUserRelationssRelatedByFollowingId = null;

            $this->collAudioPlayerSettingss = null;

            $this->collUserSettingss = null;

            $this->collUserPodcastss = null;

            $this->collUserPlaylistss = null;

            $this->collPlaylistCommentss = null;

            $this->collUserEpisodess = null;

            $this->collUserPlaylistTagss = null;

            $this->collUserEpisodeTagss = null;

            $this->collLoggings = null;

            $this->collFollowings = null;
            $this->collFollowers = null;
            $this->collPodcastss = null;
            $this->collPlaylistss = null;
            $this->collPlaylistsTagsPlaylistTags = null;
            $this->collEpisodesTagsEpisodeTags = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Users::setDeleted()
     * @see Users::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UsersTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUsersQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UsersTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                UsersTableMap::addInstanceToPool($this);
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

            if ($this->followingsScheduledForDeletion !== null) {
                if (!$this->followingsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->followingsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\Users\UserRelationsQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->followingsScheduledForDeletion = null;
                }

            }

            if ($this->collFollowings) {
                foreach ($this->collFollowings as $following) {
                    if (!$following->isDeleted() && ($following->isNew() || $following->isModified())) {
                        $following->save($con);
                    }
                }
            }


            if ($this->followersScheduledForDeletion !== null) {
                if (!$this->followersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->followersScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\Users\UserRelationsQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->followersScheduledForDeletion = null;
                }

            }

            if ($this->collFollowers) {
                foreach ($this->collFollowers as $follower) {
                    if (!$follower->isDeleted() && ($follower->isNew() || $follower->isModified())) {
                        $follower->save($con);
                    }
                }
            }


            if ($this->podcastssScheduledForDeletion !== null) {
                if (!$this->podcastssScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->podcastssScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\UserPodcasts\UserPodcastsQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->podcastssScheduledForDeletion = null;
                }

            }

            if ($this->collPodcastss) {
                foreach ($this->collPodcastss as $podcasts) {
                    if (!$podcasts->isDeleted() && ($podcasts->isNew() || $podcasts->isModified())) {
                        $podcasts->save($con);
                    }
                }
            }


            if ($this->playlistssScheduledForDeletion !== null) {
                if (!$this->playlistssScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->playlistssScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\Playlists\UserPlaylistsQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->playlistssScheduledForDeletion = null;
                }

            }

            if ($this->collPlaylistss) {
                foreach ($this->collPlaylistss as $playlists) {
                    if (!$playlists->isDeleted() && ($playlists->isNew() || $playlists->isModified())) {
                        $playlists->save($con);
                    }
                }
            }


            if ($this->combinationCollPlaylistsTagsPlaylistTagsScheduledForDeletion !== null) {
                if (!$this->combinationCollPlaylistsTagsPlaylistTagsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->combinationCollPlaylistsTagsPlaylistTagsScheduledForDeletion as $combination) {
                        $entryPk = [];

                        $entryPk[2] = $this->getId();
                        $entryPk[1] = $combination[0]->getId();
                        $entryPk[0] = $combination[1]->getId();

                        $pks[] = $entryPk;
                    }

                    \Models\UserTags\UserPlaylistTagsQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->combinationCollPlaylistsTagsPlaylistTagsScheduledForDeletion = null;
                }

            }

            if (null !== $this->combinationCollPlaylistsTagsPlaylistTags) {
                foreach ($this->combinationCollPlaylistsTagsPlaylistTags as $combination) {

                    //$combination[0] = Playlists (user_playlist_tags_fk_e258c7)
                    if (!$combination[0]->isDeleted() && ($combination[0]->isNew() || $combination[0]->isModified())) {
                        $combination[0]->save($con);
                    }

                    //$combination[1] = Tags (user_playlist_tags_fk_6bac06)
                    if (!$combination[1]->isDeleted() && ($combination[1]->isNew() || $combination[1]->isModified())) {
                        $combination[1]->save($con);
                    }

                }
            }


            if ($this->combinationCollEpisodesTagsEpisodeTagsScheduledForDeletion !== null) {
                if (!$this->combinationCollEpisodesTagsEpisodeTagsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->combinationCollEpisodesTagsEpisodeTagsScheduledForDeletion as $combination) {
                        $entryPk = [];

                        $entryPk[2] = $this->getId();
                        $entryPk[1] = $combination[0]->getId();
                        $entryPk[0] = $combination[1]->getId();

                        $pks[] = $entryPk;
                    }

                    \Models\UserTags\UserEpisodeTagsQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->combinationCollEpisodesTagsEpisodeTagsScheduledForDeletion = null;
                }

            }

            if (null !== $this->combinationCollEpisodesTagsEpisodeTags) {
                foreach ($this->combinationCollEpisodesTagsEpisodeTags as $combination) {

                    //$combination[0] = Episodes (user_episode_tags_fk_4e8703)
                    if (!$combination[0]->isDeleted() && ($combination[0]->isNew() || $combination[0]->isModified())) {
                        $combination[0]->save($con);
                    }

                    //$combination[1] = Tags (user_episode_tags_fk_6bac06)
                    if (!$combination[1]->isDeleted() && ($combination[1]->isNew() || $combination[1]->isModified())) {
                        $combination[1]->save($con);
                    }

                }
            }


            if ($this->userSessionssScheduledForDeletion !== null) {
                if (!$this->userSessionssScheduledForDeletion->isEmpty()) {
                    \Models\Users\UserSessionsQuery::create()
                        ->filterByPrimaryKeys($this->userSessionssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userSessionssScheduledForDeletion = null;
                }
            }

            if ($this->collUserSessionss !== null) {
                foreach ($this->collUserSessionss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userRelationssRelatedByFollowerIdScheduledForDeletion !== null) {
                if (!$this->userRelationssRelatedByFollowerIdScheduledForDeletion->isEmpty()) {
                    \Models\Users\UserRelationsQuery::create()
                        ->filterByPrimaryKeys($this->userRelationssRelatedByFollowerIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userRelationssRelatedByFollowerIdScheduledForDeletion = null;
                }
            }

            if ($this->collUserRelationssRelatedByFollowerId !== null) {
                foreach ($this->collUserRelationssRelatedByFollowerId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userRelationssRelatedByFollowingIdScheduledForDeletion !== null) {
                if (!$this->userRelationssRelatedByFollowingIdScheduledForDeletion->isEmpty()) {
                    \Models\Users\UserRelationsQuery::create()
                        ->filterByPrimaryKeys($this->userRelationssRelatedByFollowingIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userRelationssRelatedByFollowingIdScheduledForDeletion = null;
                }
            }

            if ($this->collUserRelationssRelatedByFollowingId !== null) {
                foreach ($this->collUserRelationssRelatedByFollowingId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->audioPlayerSettingssScheduledForDeletion !== null) {
                if (!$this->audioPlayerSettingssScheduledForDeletion->isEmpty()) {
                    \Models\AudioPlayerSettings\AudioPlayerSettingsQuery::create()
                        ->filterByPrimaryKeys($this->audioPlayerSettingssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->audioPlayerSettingssScheduledForDeletion = null;
                }
            }

            if ($this->collAudioPlayerSettingss !== null) {
                foreach ($this->collAudioPlayerSettingss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userSettingssScheduledForDeletion !== null) {
                if (!$this->userSettingssScheduledForDeletion->isEmpty()) {
                    \Models\UserSettings\UserSettingsQuery::create()
                        ->filterByPrimaryKeys($this->userSettingssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userSettingssScheduledForDeletion = null;
                }
            }

            if ($this->collUserSettingss !== null) {
                foreach ($this->collUserSettingss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userPodcastssScheduledForDeletion !== null) {
                if (!$this->userPodcastssScheduledForDeletion->isEmpty()) {
                    \Models\UserPodcasts\UserPodcastsQuery::create()
                        ->filterByPrimaryKeys($this->userPodcastssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userPodcastssScheduledForDeletion = null;
                }
            }

            if ($this->collUserPodcastss !== null) {
                foreach ($this->collUserPodcastss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
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

            if ($this->loggingsScheduledForDeletion !== null) {
                if (!$this->loggingsScheduledForDeletion->isEmpty()) {
                    \Models\Logging\LoggingQuery::create()
                        ->filterByPrimaryKeys($this->loggingsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->loggingsScheduledForDeletion = null;
                }
            }

            if ($this->collLoggings !== null) {
                foreach ($this->collLoggings as $referrerFK) {
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

        $this->modifiedColumns[UsersTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UsersTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UsersTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UsersTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(UsersTableMap::COL_FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'first_name';
        }
        if ($this->isColumnModified(UsersTableMap::COL_LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'last_name';
        }
        if ($this->isColumnModified(UsersTableMap::COL_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = 'active';
        }
        if ($this->isColumnModified(UsersTableMap::COL_GOOGLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'google_id';
        }
        if ($this->isColumnModified(UsersTableMap::COL_IMAGE_URL)) {
            $modifiedColumns[':p' . $index++]  = 'image_url';
        }

        $sql = sprintf(
            'INSERT INTO users (%s) VALUES (%s)',
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
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'first_name':
                        $stmt->bindValue($identifier, $this->first_name, PDO::PARAM_STR);
                        break;
                    case 'last_name':
                        $stmt->bindValue($identifier, $this->last_name, PDO::PARAM_STR);
                        break;
                    case 'active':
                        $stmt->bindValue($identifier, $this->active, PDO::PARAM_INT);
                        break;
                    case 'google_id':
                        $stmt->bindValue($identifier, $this->google_id, PDO::PARAM_STR);
                        break;
                    case 'image_url':
                        $stmt->bindValue($identifier, $this->image_url, PDO::PARAM_STR);
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
        $pos = UsersTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEmail();
                break;
            case 2:
                return $this->getFirstName();
                break;
            case 3:
                return $this->getLastName();
                break;
            case 4:
                return $this->getActive();
                break;
            case 5:
                return $this->getGoogleId();
                break;
            case 6:
                return $this->getImageUrl();
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

        if (isset($alreadyDumpedObjects['Users'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Users'][$this->hashCode()] = true;
        $keys = UsersTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getEmail(),
            $keys[2] => $this->getFirstName(),
            $keys[3] => $this->getLastName(),
            $keys[4] => $this->getActive(),
            $keys[5] => $this->getGoogleId(),
            $keys[6] => $this->getImageUrl(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collUserSessionss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userSessionss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_sessionss';
                        break;
                    default:
                        $key = 'UserSessionss';
                }

                $result[$key] = $this->collUserSessionss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserRelationssRelatedByFollowerId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userRelationss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_relationss';
                        break;
                    default:
                        $key = 'UserRelationss';
                }

                $result[$key] = $this->collUserRelationssRelatedByFollowerId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserRelationssRelatedByFollowingId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userRelationss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_relationss';
                        break;
                    default:
                        $key = 'UserRelationss';
                }

                $result[$key] = $this->collUserRelationssRelatedByFollowingId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAudioPlayerSettingss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'audioPlayerSettingss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'audio_player_settingss';
                        break;
                    default:
                        $key = 'AudioPlayerSettingss';
                }

                $result[$key] = $this->collAudioPlayerSettingss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserSettingss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userSettingss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_settingss';
                        break;
                    default:
                        $key = 'UserSettingss';
                }

                $result[$key] = $this->collUserSettingss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserPodcastss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userPodcastss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_podcastss';
                        break;
                    default:
                        $key = 'UserPodcastss';
                }

                $result[$key] = $this->collUserPodcastss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
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
            if (null !== $this->collLoggings) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'loggings';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'loggings';
                        break;
                    default:
                        $key = 'Loggings';
                }

                $result[$key] = $this->collLoggings->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Models\Users\Users
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UsersTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\Users\Users
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setEmail($value);
                break;
            case 2:
                $this->setFirstName($value);
                break;
            case 3:
                $this->setLastName($value);
                break;
            case 4:
                $this->setActive($value);
                break;
            case 5:
                $this->setGoogleId($value);
                break;
            case 6:
                $this->setImageUrl($value);
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
        $keys = UsersTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEmail($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setFirstName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setLastName($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setActive($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setGoogleId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setImageUrl($arr[$keys[6]]);
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
     * @return $this|\Models\Users\Users The current object, for fluid interface
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
        $criteria = new Criteria(UsersTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UsersTableMap::COL_ID)) {
            $criteria->add(UsersTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UsersTableMap::COL_EMAIL)) {
            $criteria->add(UsersTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UsersTableMap::COL_FIRST_NAME)) {
            $criteria->add(UsersTableMap::COL_FIRST_NAME, $this->first_name);
        }
        if ($this->isColumnModified(UsersTableMap::COL_LAST_NAME)) {
            $criteria->add(UsersTableMap::COL_LAST_NAME, $this->last_name);
        }
        if ($this->isColumnModified(UsersTableMap::COL_ACTIVE)) {
            $criteria->add(UsersTableMap::COL_ACTIVE, $this->active);
        }
        if ($this->isColumnModified(UsersTableMap::COL_GOOGLE_ID)) {
            $criteria->add(UsersTableMap::COL_GOOGLE_ID, $this->google_id);
        }
        if ($this->isColumnModified(UsersTableMap::COL_IMAGE_URL)) {
            $criteria->add(UsersTableMap::COL_IMAGE_URL, $this->image_url);
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
        $criteria = ChildUsersQuery::create();
        $criteria->add(UsersTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Models\Users\Users (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEmail($this->getEmail());
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setActive($this->getActive());
        $copyObj->setGoogleId($this->getGoogleId());
        $copyObj->setImageUrl($this->getImageUrl());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUserSessionss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserSessions($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserRelationssRelatedByFollowerId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserRelationsRelatedByFollowerId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserRelationssRelatedByFollowingId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserRelationsRelatedByFollowingId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAudioPlayerSettingss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAudioPlayerSettings($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserSettingss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserSettings($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserPodcastss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserPodcasts($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserPlaylistss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserPlaylists($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPlaylistCommentss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPlaylistComments($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserEpisodess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserEpisodes($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserPlaylistTagss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserPlaylistTags($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserEpisodeTagss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserEpisodeTags($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLoggings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLogging($relObj->copy($deepCopy));
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
     * @return \Models\Users\Users Clone of current object.
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
        if ('UserSessions' == $relationName) {
            $this->initUserSessionss();
            return;
        }
        if ('UserRelationsRelatedByFollowerId' == $relationName) {
            $this->initUserRelationssRelatedByFollowerId();
            return;
        }
        if ('UserRelationsRelatedByFollowingId' == $relationName) {
            $this->initUserRelationssRelatedByFollowingId();
            return;
        }
        if ('AudioPlayerSettings' == $relationName) {
            $this->initAudioPlayerSettingss();
            return;
        }
        if ('UserSettings' == $relationName) {
            $this->initUserSettingss();
            return;
        }
        if ('UserPodcasts' == $relationName) {
            $this->initUserPodcastss();
            return;
        }
        if ('UserPlaylists' == $relationName) {
            $this->initUserPlaylistss();
            return;
        }
        if ('PlaylistComments' == $relationName) {
            $this->initPlaylistCommentss();
            return;
        }
        if ('UserEpisodes' == $relationName) {
            $this->initUserEpisodess();
            return;
        }
        if ('UserPlaylistTags' == $relationName) {
            $this->initUserPlaylistTagss();
            return;
        }
        if ('UserEpisodeTags' == $relationName) {
            $this->initUserEpisodeTagss();
            return;
        }
        if ('Logging' == $relationName) {
            $this->initLoggings();
            return;
        }
    }

    /**
     * Clears out the collUserSessionss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserSessionss()
     */
    public function clearUserSessionss()
    {
        $this->collUserSessionss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserSessionss collection loaded partially.
     */
    public function resetPartialUserSessionss($v = true)
    {
        $this->collUserSessionssPartial = $v;
    }

    /**
     * Initializes the collUserSessionss collection.
     *
     * By default this just sets the collUserSessionss collection to an empty array (like clearcollUserSessionss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserSessionss($overrideExisting = true)
    {
        if (null !== $this->collUserSessionss && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserSessionsTableMap::getTableMap()->getCollectionClassName();

        $this->collUserSessionss = new $collectionClassName;
        $this->collUserSessionss->setModel('\Models\Users\UserSessions');
    }

    /**
     * Gets an array of ChildUserSessions objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserSessions[] List of ChildUserSessions objects
     * @throws PropelException
     */
    public function getUserSessionss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserSessionssPartial && !$this->isNew();
        if (null === $this->collUserSessionss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserSessionss) {
                // return empty collection
                $this->initUserSessionss();
            } else {
                $collUserSessionss = ChildUserSessionsQuery::create(null, $criteria)
                    ->filterByUsers($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserSessionssPartial && count($collUserSessionss)) {
                        $this->initUserSessionss(false);

                        foreach ($collUserSessionss as $obj) {
                            if (false == $this->collUserSessionss->contains($obj)) {
                                $this->collUserSessionss->append($obj);
                            }
                        }

                        $this->collUserSessionssPartial = true;
                    }

                    return $collUserSessionss;
                }

                if ($partial && $this->collUserSessionss) {
                    foreach ($this->collUserSessionss as $obj) {
                        if ($obj->isNew()) {
                            $collUserSessionss[] = $obj;
                        }
                    }
                }

                $this->collUserSessionss = $collUserSessionss;
                $this->collUserSessionssPartial = false;
            }
        }

        return $this->collUserSessionss;
    }

    /**
     * Sets a collection of ChildUserSessions objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userSessionss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setUserSessionss(Collection $userSessionss, ConnectionInterface $con = null)
    {
        /** @var ChildUserSessions[] $userSessionssToDelete */
        $userSessionssToDelete = $this->getUserSessionss(new Criteria(), $con)->diff($userSessionss);


        $this->userSessionssScheduledForDeletion = $userSessionssToDelete;

        foreach ($userSessionssToDelete as $userSessionsRemoved) {
            $userSessionsRemoved->setUsers(null);
        }

        $this->collUserSessionss = null;
        foreach ($userSessionss as $userSessions) {
            $this->addUserSessions($userSessions);
        }

        $this->collUserSessionss = $userSessionss;
        $this->collUserSessionssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserSessions objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserSessions objects.
     * @throws PropelException
     */
    public function countUserSessionss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserSessionssPartial && !$this->isNew();
        if (null === $this->collUserSessionss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserSessionss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserSessionss());
            }

            $query = ChildUserSessionsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collUserSessionss);
    }

    /**
     * Method called to associate a ChildUserSessions object to this object
     * through the ChildUserSessions foreign key attribute.
     *
     * @param  ChildUserSessions $l ChildUserSessions
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function addUserSessions(ChildUserSessions $l)
    {
        if ($this->collUserSessionss === null) {
            $this->initUserSessionss();
            $this->collUserSessionssPartial = true;
        }

        if (!$this->collUserSessionss->contains($l)) {
            $this->doAddUserSessions($l);

            if ($this->userSessionssScheduledForDeletion and $this->userSessionssScheduledForDeletion->contains($l)) {
                $this->userSessionssScheduledForDeletion->remove($this->userSessionssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserSessions $userSessions The ChildUserSessions object to add.
     */
    protected function doAddUserSessions(ChildUserSessions $userSessions)
    {
        $this->collUserSessionss[]= $userSessions;
        $userSessions->setUsers($this);
    }

    /**
     * @param  ChildUserSessions $userSessions The ChildUserSessions object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeUserSessions(ChildUserSessions $userSessions)
    {
        if ($this->getUserSessionss()->contains($userSessions)) {
            $pos = $this->collUserSessionss->search($userSessions);
            $this->collUserSessionss->remove($pos);
            if (null === $this->userSessionssScheduledForDeletion) {
                $this->userSessionssScheduledForDeletion = clone $this->collUserSessionss;
                $this->userSessionssScheduledForDeletion->clear();
            }
            $this->userSessionssScheduledForDeletion[]= clone $userSessions;
            $userSessions->setUsers(null);
        }

        return $this;
    }

    /**
     * Clears out the collUserRelationssRelatedByFollowerId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserRelationssRelatedByFollowerId()
     */
    public function clearUserRelationssRelatedByFollowerId()
    {
        $this->collUserRelationssRelatedByFollowerId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserRelationssRelatedByFollowerId collection loaded partially.
     */
    public function resetPartialUserRelationssRelatedByFollowerId($v = true)
    {
        $this->collUserRelationssRelatedByFollowerIdPartial = $v;
    }

    /**
     * Initializes the collUserRelationssRelatedByFollowerId collection.
     *
     * By default this just sets the collUserRelationssRelatedByFollowerId collection to an empty array (like clearcollUserRelationssRelatedByFollowerId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserRelationssRelatedByFollowerId($overrideExisting = true)
    {
        if (null !== $this->collUserRelationssRelatedByFollowerId && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserRelationsTableMap::getTableMap()->getCollectionClassName();

        $this->collUserRelationssRelatedByFollowerId = new $collectionClassName;
        $this->collUserRelationssRelatedByFollowerId->setModel('\Models\Users\UserRelations');
    }

    /**
     * Gets an array of ChildUserRelations objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserRelations[] List of ChildUserRelations objects
     * @throws PropelException
     */
    public function getUserRelationssRelatedByFollowerId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserRelationssRelatedByFollowerIdPartial && !$this->isNew();
        if (null === $this->collUserRelationssRelatedByFollowerId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserRelationssRelatedByFollowerId) {
                // return empty collection
                $this->initUserRelationssRelatedByFollowerId();
            } else {
                $collUserRelationssRelatedByFollowerId = ChildUserRelationsQuery::create(null, $criteria)
                    ->filterByFollower($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserRelationssRelatedByFollowerIdPartial && count($collUserRelationssRelatedByFollowerId)) {
                        $this->initUserRelationssRelatedByFollowerId(false);

                        foreach ($collUserRelationssRelatedByFollowerId as $obj) {
                            if (false == $this->collUserRelationssRelatedByFollowerId->contains($obj)) {
                                $this->collUserRelationssRelatedByFollowerId->append($obj);
                            }
                        }

                        $this->collUserRelationssRelatedByFollowerIdPartial = true;
                    }

                    return $collUserRelationssRelatedByFollowerId;
                }

                if ($partial && $this->collUserRelationssRelatedByFollowerId) {
                    foreach ($this->collUserRelationssRelatedByFollowerId as $obj) {
                        if ($obj->isNew()) {
                            $collUserRelationssRelatedByFollowerId[] = $obj;
                        }
                    }
                }

                $this->collUserRelationssRelatedByFollowerId = $collUserRelationssRelatedByFollowerId;
                $this->collUserRelationssRelatedByFollowerIdPartial = false;
            }
        }

        return $this->collUserRelationssRelatedByFollowerId;
    }

    /**
     * Sets a collection of ChildUserRelations objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userRelationssRelatedByFollowerId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setUserRelationssRelatedByFollowerId(Collection $userRelationssRelatedByFollowerId, ConnectionInterface $con = null)
    {
        /** @var ChildUserRelations[] $userRelationssRelatedByFollowerIdToDelete */
        $userRelationssRelatedByFollowerIdToDelete = $this->getUserRelationssRelatedByFollowerId(new Criteria(), $con)->diff($userRelationssRelatedByFollowerId);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userRelationssRelatedByFollowerIdScheduledForDeletion = clone $userRelationssRelatedByFollowerIdToDelete;

        foreach ($userRelationssRelatedByFollowerIdToDelete as $userRelationsRelatedByFollowerIdRemoved) {
            $userRelationsRelatedByFollowerIdRemoved->setFollower(null);
        }

        $this->collUserRelationssRelatedByFollowerId = null;
        foreach ($userRelationssRelatedByFollowerId as $userRelationsRelatedByFollowerId) {
            $this->addUserRelationsRelatedByFollowerId($userRelationsRelatedByFollowerId);
        }

        $this->collUserRelationssRelatedByFollowerId = $userRelationssRelatedByFollowerId;
        $this->collUserRelationssRelatedByFollowerIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserRelations objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserRelations objects.
     * @throws PropelException
     */
    public function countUserRelationssRelatedByFollowerId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserRelationssRelatedByFollowerIdPartial && !$this->isNew();
        if (null === $this->collUserRelationssRelatedByFollowerId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserRelationssRelatedByFollowerId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserRelationssRelatedByFollowerId());
            }

            $query = ChildUserRelationsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFollower($this)
                ->count($con);
        }

        return count($this->collUserRelationssRelatedByFollowerId);
    }

    /**
     * Method called to associate a ChildUserRelations object to this object
     * through the ChildUserRelations foreign key attribute.
     *
     * @param  ChildUserRelations $l ChildUserRelations
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function addUserRelationsRelatedByFollowerId(ChildUserRelations $l)
    {
        if ($this->collUserRelationssRelatedByFollowerId === null) {
            $this->initUserRelationssRelatedByFollowerId();
            $this->collUserRelationssRelatedByFollowerIdPartial = true;
        }

        if (!$this->collUserRelationssRelatedByFollowerId->contains($l)) {
            $this->doAddUserRelationsRelatedByFollowerId($l);

            if ($this->userRelationssRelatedByFollowerIdScheduledForDeletion and $this->userRelationssRelatedByFollowerIdScheduledForDeletion->contains($l)) {
                $this->userRelationssRelatedByFollowerIdScheduledForDeletion->remove($this->userRelationssRelatedByFollowerIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserRelations $userRelationsRelatedByFollowerId The ChildUserRelations object to add.
     */
    protected function doAddUserRelationsRelatedByFollowerId(ChildUserRelations $userRelationsRelatedByFollowerId)
    {
        $this->collUserRelationssRelatedByFollowerId[]= $userRelationsRelatedByFollowerId;
        $userRelationsRelatedByFollowerId->setFollower($this);
    }

    /**
     * @param  ChildUserRelations $userRelationsRelatedByFollowerId The ChildUserRelations object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeUserRelationsRelatedByFollowerId(ChildUserRelations $userRelationsRelatedByFollowerId)
    {
        if ($this->getUserRelationssRelatedByFollowerId()->contains($userRelationsRelatedByFollowerId)) {
            $pos = $this->collUserRelationssRelatedByFollowerId->search($userRelationsRelatedByFollowerId);
            $this->collUserRelationssRelatedByFollowerId->remove($pos);
            if (null === $this->userRelationssRelatedByFollowerIdScheduledForDeletion) {
                $this->userRelationssRelatedByFollowerIdScheduledForDeletion = clone $this->collUserRelationssRelatedByFollowerId;
                $this->userRelationssRelatedByFollowerIdScheduledForDeletion->clear();
            }
            $this->userRelationssRelatedByFollowerIdScheduledForDeletion[]= clone $userRelationsRelatedByFollowerId;
            $userRelationsRelatedByFollowerId->setFollower(null);
        }

        return $this;
    }

    /**
     * Clears out the collUserRelationssRelatedByFollowingId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserRelationssRelatedByFollowingId()
     */
    public function clearUserRelationssRelatedByFollowingId()
    {
        $this->collUserRelationssRelatedByFollowingId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserRelationssRelatedByFollowingId collection loaded partially.
     */
    public function resetPartialUserRelationssRelatedByFollowingId($v = true)
    {
        $this->collUserRelationssRelatedByFollowingIdPartial = $v;
    }

    /**
     * Initializes the collUserRelationssRelatedByFollowingId collection.
     *
     * By default this just sets the collUserRelationssRelatedByFollowingId collection to an empty array (like clearcollUserRelationssRelatedByFollowingId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserRelationssRelatedByFollowingId($overrideExisting = true)
    {
        if (null !== $this->collUserRelationssRelatedByFollowingId && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserRelationsTableMap::getTableMap()->getCollectionClassName();

        $this->collUserRelationssRelatedByFollowingId = new $collectionClassName;
        $this->collUserRelationssRelatedByFollowingId->setModel('\Models\Users\UserRelations');
    }

    /**
     * Gets an array of ChildUserRelations objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserRelations[] List of ChildUserRelations objects
     * @throws PropelException
     */
    public function getUserRelationssRelatedByFollowingId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserRelationssRelatedByFollowingIdPartial && !$this->isNew();
        if (null === $this->collUserRelationssRelatedByFollowingId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserRelationssRelatedByFollowingId) {
                // return empty collection
                $this->initUserRelationssRelatedByFollowingId();
            } else {
                $collUserRelationssRelatedByFollowingId = ChildUserRelationsQuery::create(null, $criteria)
                    ->filterByFollowing($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserRelationssRelatedByFollowingIdPartial && count($collUserRelationssRelatedByFollowingId)) {
                        $this->initUserRelationssRelatedByFollowingId(false);

                        foreach ($collUserRelationssRelatedByFollowingId as $obj) {
                            if (false == $this->collUserRelationssRelatedByFollowingId->contains($obj)) {
                                $this->collUserRelationssRelatedByFollowingId->append($obj);
                            }
                        }

                        $this->collUserRelationssRelatedByFollowingIdPartial = true;
                    }

                    return $collUserRelationssRelatedByFollowingId;
                }

                if ($partial && $this->collUserRelationssRelatedByFollowingId) {
                    foreach ($this->collUserRelationssRelatedByFollowingId as $obj) {
                        if ($obj->isNew()) {
                            $collUserRelationssRelatedByFollowingId[] = $obj;
                        }
                    }
                }

                $this->collUserRelationssRelatedByFollowingId = $collUserRelationssRelatedByFollowingId;
                $this->collUserRelationssRelatedByFollowingIdPartial = false;
            }
        }

        return $this->collUserRelationssRelatedByFollowingId;
    }

    /**
     * Sets a collection of ChildUserRelations objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userRelationssRelatedByFollowingId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setUserRelationssRelatedByFollowingId(Collection $userRelationssRelatedByFollowingId, ConnectionInterface $con = null)
    {
        /** @var ChildUserRelations[] $userRelationssRelatedByFollowingIdToDelete */
        $userRelationssRelatedByFollowingIdToDelete = $this->getUserRelationssRelatedByFollowingId(new Criteria(), $con)->diff($userRelationssRelatedByFollowingId);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userRelationssRelatedByFollowingIdScheduledForDeletion = clone $userRelationssRelatedByFollowingIdToDelete;

        foreach ($userRelationssRelatedByFollowingIdToDelete as $userRelationsRelatedByFollowingIdRemoved) {
            $userRelationsRelatedByFollowingIdRemoved->setFollowing(null);
        }

        $this->collUserRelationssRelatedByFollowingId = null;
        foreach ($userRelationssRelatedByFollowingId as $userRelationsRelatedByFollowingId) {
            $this->addUserRelationsRelatedByFollowingId($userRelationsRelatedByFollowingId);
        }

        $this->collUserRelationssRelatedByFollowingId = $userRelationssRelatedByFollowingId;
        $this->collUserRelationssRelatedByFollowingIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserRelations objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserRelations objects.
     * @throws PropelException
     */
    public function countUserRelationssRelatedByFollowingId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserRelationssRelatedByFollowingIdPartial && !$this->isNew();
        if (null === $this->collUserRelationssRelatedByFollowingId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserRelationssRelatedByFollowingId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserRelationssRelatedByFollowingId());
            }

            $query = ChildUserRelationsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFollowing($this)
                ->count($con);
        }

        return count($this->collUserRelationssRelatedByFollowingId);
    }

    /**
     * Method called to associate a ChildUserRelations object to this object
     * through the ChildUserRelations foreign key attribute.
     *
     * @param  ChildUserRelations $l ChildUserRelations
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function addUserRelationsRelatedByFollowingId(ChildUserRelations $l)
    {
        if ($this->collUserRelationssRelatedByFollowingId === null) {
            $this->initUserRelationssRelatedByFollowingId();
            $this->collUserRelationssRelatedByFollowingIdPartial = true;
        }

        if (!$this->collUserRelationssRelatedByFollowingId->contains($l)) {
            $this->doAddUserRelationsRelatedByFollowingId($l);

            if ($this->userRelationssRelatedByFollowingIdScheduledForDeletion and $this->userRelationssRelatedByFollowingIdScheduledForDeletion->contains($l)) {
                $this->userRelationssRelatedByFollowingIdScheduledForDeletion->remove($this->userRelationssRelatedByFollowingIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUserRelations $userRelationsRelatedByFollowingId The ChildUserRelations object to add.
     */
    protected function doAddUserRelationsRelatedByFollowingId(ChildUserRelations $userRelationsRelatedByFollowingId)
    {
        $this->collUserRelationssRelatedByFollowingId[]= $userRelationsRelatedByFollowingId;
        $userRelationsRelatedByFollowingId->setFollowing($this);
    }

    /**
     * @param  ChildUserRelations $userRelationsRelatedByFollowingId The ChildUserRelations object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeUserRelationsRelatedByFollowingId(ChildUserRelations $userRelationsRelatedByFollowingId)
    {
        if ($this->getUserRelationssRelatedByFollowingId()->contains($userRelationsRelatedByFollowingId)) {
            $pos = $this->collUserRelationssRelatedByFollowingId->search($userRelationsRelatedByFollowingId);
            $this->collUserRelationssRelatedByFollowingId->remove($pos);
            if (null === $this->userRelationssRelatedByFollowingIdScheduledForDeletion) {
                $this->userRelationssRelatedByFollowingIdScheduledForDeletion = clone $this->collUserRelationssRelatedByFollowingId;
                $this->userRelationssRelatedByFollowingIdScheduledForDeletion->clear();
            }
            $this->userRelationssRelatedByFollowingIdScheduledForDeletion[]= clone $userRelationsRelatedByFollowingId;
            $userRelationsRelatedByFollowingId->setFollowing(null);
        }

        return $this;
    }

    /**
     * Clears out the collAudioPlayerSettingss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAudioPlayerSettingss()
     */
    public function clearAudioPlayerSettingss()
    {
        $this->collAudioPlayerSettingss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAudioPlayerSettingss collection loaded partially.
     */
    public function resetPartialAudioPlayerSettingss($v = true)
    {
        $this->collAudioPlayerSettingssPartial = $v;
    }

    /**
     * Initializes the collAudioPlayerSettingss collection.
     *
     * By default this just sets the collAudioPlayerSettingss collection to an empty array (like clearcollAudioPlayerSettingss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAudioPlayerSettingss($overrideExisting = true)
    {
        if (null !== $this->collAudioPlayerSettingss && !$overrideExisting) {
            return;
        }

        $collectionClassName = AudioPlayerSettingsTableMap::getTableMap()->getCollectionClassName();

        $this->collAudioPlayerSettingss = new $collectionClassName;
        $this->collAudioPlayerSettingss->setModel('\Models\AudioPlayerSettings\AudioPlayerSettings');
    }

    /**
     * Gets an array of AudioPlayerSettings objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|AudioPlayerSettings[] List of AudioPlayerSettings objects
     * @throws PropelException
     */
    public function getAudioPlayerSettingss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAudioPlayerSettingssPartial && !$this->isNew();
        if (null === $this->collAudioPlayerSettingss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAudioPlayerSettingss) {
                // return empty collection
                $this->initAudioPlayerSettingss();
            } else {
                $collAudioPlayerSettingss = AudioPlayerSettingsQuery::create(null, $criteria)
                    ->filterByUsers($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAudioPlayerSettingssPartial && count($collAudioPlayerSettingss)) {
                        $this->initAudioPlayerSettingss(false);

                        foreach ($collAudioPlayerSettingss as $obj) {
                            if (false == $this->collAudioPlayerSettingss->contains($obj)) {
                                $this->collAudioPlayerSettingss->append($obj);
                            }
                        }

                        $this->collAudioPlayerSettingssPartial = true;
                    }

                    return $collAudioPlayerSettingss;
                }

                if ($partial && $this->collAudioPlayerSettingss) {
                    foreach ($this->collAudioPlayerSettingss as $obj) {
                        if ($obj->isNew()) {
                            $collAudioPlayerSettingss[] = $obj;
                        }
                    }
                }

                $this->collAudioPlayerSettingss = $collAudioPlayerSettingss;
                $this->collAudioPlayerSettingssPartial = false;
            }
        }

        return $this->collAudioPlayerSettingss;
    }

    /**
     * Sets a collection of AudioPlayerSettings objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $audioPlayerSettingss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setAudioPlayerSettingss(Collection $audioPlayerSettingss, ConnectionInterface $con = null)
    {
        /** @var AudioPlayerSettings[] $audioPlayerSettingssToDelete */
        $audioPlayerSettingssToDelete = $this->getAudioPlayerSettingss(new Criteria(), $con)->diff($audioPlayerSettingss);


        $this->audioPlayerSettingssScheduledForDeletion = $audioPlayerSettingssToDelete;

        foreach ($audioPlayerSettingssToDelete as $audioPlayerSettingsRemoved) {
            $audioPlayerSettingsRemoved->setUsers(null);
        }

        $this->collAudioPlayerSettingss = null;
        foreach ($audioPlayerSettingss as $audioPlayerSettings) {
            $this->addAudioPlayerSettings($audioPlayerSettings);
        }

        $this->collAudioPlayerSettingss = $audioPlayerSettingss;
        $this->collAudioPlayerSettingssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseAudioPlayerSettings objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseAudioPlayerSettings objects.
     * @throws PropelException
     */
    public function countAudioPlayerSettingss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAudioPlayerSettingssPartial && !$this->isNew();
        if (null === $this->collAudioPlayerSettingss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAudioPlayerSettingss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAudioPlayerSettingss());
            }

            $query = AudioPlayerSettingsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collAudioPlayerSettingss);
    }

    /**
     * Method called to associate a AudioPlayerSettings object to this object
     * through the AudioPlayerSettings foreign key attribute.
     *
     * @param  AudioPlayerSettings $l AudioPlayerSettings
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function addAudioPlayerSettings(AudioPlayerSettings $l)
    {
        if ($this->collAudioPlayerSettingss === null) {
            $this->initAudioPlayerSettingss();
            $this->collAudioPlayerSettingssPartial = true;
        }

        if (!$this->collAudioPlayerSettingss->contains($l)) {
            $this->doAddAudioPlayerSettings($l);

            if ($this->audioPlayerSettingssScheduledForDeletion and $this->audioPlayerSettingssScheduledForDeletion->contains($l)) {
                $this->audioPlayerSettingssScheduledForDeletion->remove($this->audioPlayerSettingssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param AudioPlayerSettings $audioPlayerSettings The AudioPlayerSettings object to add.
     */
    protected function doAddAudioPlayerSettings(AudioPlayerSettings $audioPlayerSettings)
    {
        $this->collAudioPlayerSettingss[]= $audioPlayerSettings;
        $audioPlayerSettings->setUsers($this);
    }

    /**
     * @param  AudioPlayerSettings $audioPlayerSettings The AudioPlayerSettings object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeAudioPlayerSettings(AudioPlayerSettings $audioPlayerSettings)
    {
        if ($this->getAudioPlayerSettingss()->contains($audioPlayerSettings)) {
            $pos = $this->collAudioPlayerSettingss->search($audioPlayerSettings);
            $this->collAudioPlayerSettingss->remove($pos);
            if (null === $this->audioPlayerSettingssScheduledForDeletion) {
                $this->audioPlayerSettingssScheduledForDeletion = clone $this->collAudioPlayerSettingss;
                $this->audioPlayerSettingssScheduledForDeletion->clear();
            }
            $this->audioPlayerSettingssScheduledForDeletion[]= clone $audioPlayerSettings;
            $audioPlayerSettings->setUsers(null);
        }

        return $this;
    }

    /**
     * Clears out the collUserSettingss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserSettingss()
     */
    public function clearUserSettingss()
    {
        $this->collUserSettingss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserSettingss collection loaded partially.
     */
    public function resetPartialUserSettingss($v = true)
    {
        $this->collUserSettingssPartial = $v;
    }

    /**
     * Initializes the collUserSettingss collection.
     *
     * By default this just sets the collUserSettingss collection to an empty array (like clearcollUserSettingss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserSettingss($overrideExisting = true)
    {
        if (null !== $this->collUserSettingss && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserSettingsTableMap::getTableMap()->getCollectionClassName();

        $this->collUserSettingss = new $collectionClassName;
        $this->collUserSettingss->setModel('\Models\UserSettings\UserSettings');
    }

    /**
     * Gets an array of UserSettings objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|UserSettings[] List of UserSettings objects
     * @throws PropelException
     */
    public function getUserSettingss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserSettingssPartial && !$this->isNew();
        if (null === $this->collUserSettingss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserSettingss) {
                // return empty collection
                $this->initUserSettingss();
            } else {
                $collUserSettingss = UserSettingsQuery::create(null, $criteria)
                    ->filterByUsers($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserSettingssPartial && count($collUserSettingss)) {
                        $this->initUserSettingss(false);

                        foreach ($collUserSettingss as $obj) {
                            if (false == $this->collUserSettingss->contains($obj)) {
                                $this->collUserSettingss->append($obj);
                            }
                        }

                        $this->collUserSettingssPartial = true;
                    }

                    return $collUserSettingss;
                }

                if ($partial && $this->collUserSettingss) {
                    foreach ($this->collUserSettingss as $obj) {
                        if ($obj->isNew()) {
                            $collUserSettingss[] = $obj;
                        }
                    }
                }

                $this->collUserSettingss = $collUserSettingss;
                $this->collUserSettingssPartial = false;
            }
        }

        return $this->collUserSettingss;
    }

    /**
     * Sets a collection of UserSettings objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userSettingss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setUserSettingss(Collection $userSettingss, ConnectionInterface $con = null)
    {
        /** @var UserSettings[] $userSettingssToDelete */
        $userSettingssToDelete = $this->getUserSettingss(new Criteria(), $con)->diff($userSettingss);


        $this->userSettingssScheduledForDeletion = $userSettingssToDelete;

        foreach ($userSettingssToDelete as $userSettingsRemoved) {
            $userSettingsRemoved->setUsers(null);
        }

        $this->collUserSettingss = null;
        foreach ($userSettingss as $userSettings) {
            $this->addUserSettings($userSettings);
        }

        $this->collUserSettingss = $userSettingss;
        $this->collUserSettingssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseUserSettings objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseUserSettings objects.
     * @throws PropelException
     */
    public function countUserSettingss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserSettingssPartial && !$this->isNew();
        if (null === $this->collUserSettingss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserSettingss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserSettingss());
            }

            $query = UserSettingsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collUserSettingss);
    }

    /**
     * Method called to associate a UserSettings object to this object
     * through the UserSettings foreign key attribute.
     *
     * @param  UserSettings $l UserSettings
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function addUserSettings(UserSettings $l)
    {
        if ($this->collUserSettingss === null) {
            $this->initUserSettingss();
            $this->collUserSettingssPartial = true;
        }

        if (!$this->collUserSettingss->contains($l)) {
            $this->doAddUserSettings($l);

            if ($this->userSettingssScheduledForDeletion and $this->userSettingssScheduledForDeletion->contains($l)) {
                $this->userSettingssScheduledForDeletion->remove($this->userSettingssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param UserSettings $userSettings The UserSettings object to add.
     */
    protected function doAddUserSettings(UserSettings $userSettings)
    {
        $this->collUserSettingss[]= $userSettings;
        $userSettings->setUsers($this);
    }

    /**
     * @param  UserSettings $userSettings The UserSettings object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeUserSettings(UserSettings $userSettings)
    {
        if ($this->getUserSettingss()->contains($userSettings)) {
            $pos = $this->collUserSettingss->search($userSettings);
            $this->collUserSettingss->remove($pos);
            if (null === $this->userSettingssScheduledForDeletion) {
                $this->userSettingssScheduledForDeletion = clone $this->collUserSettingss;
                $this->userSettingssScheduledForDeletion->clear();
            }
            $this->userSettingssScheduledForDeletion[]= clone $userSettings;
            $userSettings->setUsers(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related UserSettingss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|UserSettings[] List of UserSettings objects
     */
    public function getUserSettingssJoinPlans(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = UserSettingsQuery::create(null, $criteria);
        $query->joinWith('Plans', $joinBehavior);

        return $this->getUserSettingss($query, $con);
    }

    /**
     * Clears out the collUserPodcastss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserPodcastss()
     */
    public function clearUserPodcastss()
    {
        $this->collUserPodcastss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserPodcastss collection loaded partially.
     */
    public function resetPartialUserPodcastss($v = true)
    {
        $this->collUserPodcastssPartial = $v;
    }

    /**
     * Initializes the collUserPodcastss collection.
     *
     * By default this just sets the collUserPodcastss collection to an empty array (like clearcollUserPodcastss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserPodcastss($overrideExisting = true)
    {
        if (null !== $this->collUserPodcastss && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserPodcastsTableMap::getTableMap()->getCollectionClassName();

        $this->collUserPodcastss = new $collectionClassName;
        $this->collUserPodcastss->setModel('\Models\UserPodcasts\UserPodcasts');
    }

    /**
     * Gets an array of UserPodcasts objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|UserPodcasts[] List of UserPodcasts objects
     * @throws PropelException
     */
    public function getUserPodcastss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserPodcastssPartial && !$this->isNew();
        if (null === $this->collUserPodcastss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserPodcastss) {
                // return empty collection
                $this->initUserPodcastss();
            } else {
                $collUserPodcastss = UserPodcastsQuery::create(null, $criteria)
                    ->filterByUsers($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserPodcastssPartial && count($collUserPodcastss)) {
                        $this->initUserPodcastss(false);

                        foreach ($collUserPodcastss as $obj) {
                            if (false == $this->collUserPodcastss->contains($obj)) {
                                $this->collUserPodcastss->append($obj);
                            }
                        }

                        $this->collUserPodcastssPartial = true;
                    }

                    return $collUserPodcastss;
                }

                if ($partial && $this->collUserPodcastss) {
                    foreach ($this->collUserPodcastss as $obj) {
                        if ($obj->isNew()) {
                            $collUserPodcastss[] = $obj;
                        }
                    }
                }

                $this->collUserPodcastss = $collUserPodcastss;
                $this->collUserPodcastssPartial = false;
            }
        }

        return $this->collUserPodcastss;
    }

    /**
     * Sets a collection of UserPodcasts objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userPodcastss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setUserPodcastss(Collection $userPodcastss, ConnectionInterface $con = null)
    {
        /** @var UserPodcasts[] $userPodcastssToDelete */
        $userPodcastssToDelete = $this->getUserPodcastss(new Criteria(), $con)->diff($userPodcastss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userPodcastssScheduledForDeletion = clone $userPodcastssToDelete;

        foreach ($userPodcastssToDelete as $userPodcastsRemoved) {
            $userPodcastsRemoved->setUsers(null);
        }

        $this->collUserPodcastss = null;
        foreach ($userPodcastss as $userPodcasts) {
            $this->addUserPodcasts($userPodcasts);
        }

        $this->collUserPodcastss = $userPodcastss;
        $this->collUserPodcastssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseUserPodcasts objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseUserPodcasts objects.
     * @throws PropelException
     */
    public function countUserPodcastss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserPodcastssPartial && !$this->isNew();
        if (null === $this->collUserPodcastss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserPodcastss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserPodcastss());
            }

            $query = UserPodcastsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collUserPodcastss);
    }

    /**
     * Method called to associate a UserPodcasts object to this object
     * through the UserPodcasts foreign key attribute.
     *
     * @param  UserPodcasts $l UserPodcasts
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function addUserPodcasts(UserPodcasts $l)
    {
        if ($this->collUserPodcastss === null) {
            $this->initUserPodcastss();
            $this->collUserPodcastssPartial = true;
        }

        if (!$this->collUserPodcastss->contains($l)) {
            $this->doAddUserPodcasts($l);

            if ($this->userPodcastssScheduledForDeletion and $this->userPodcastssScheduledForDeletion->contains($l)) {
                $this->userPodcastssScheduledForDeletion->remove($this->userPodcastssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param UserPodcasts $userPodcasts The UserPodcasts object to add.
     */
    protected function doAddUserPodcasts(UserPodcasts $userPodcasts)
    {
        $this->collUserPodcastss[]= $userPodcasts;
        $userPodcasts->setUsers($this);
    }

    /**
     * @param  UserPodcasts $userPodcasts The UserPodcasts object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeUserPodcasts(UserPodcasts $userPodcasts)
    {
        if ($this->getUserPodcastss()->contains($userPodcasts)) {
            $pos = $this->collUserPodcastss->search($userPodcasts);
            $this->collUserPodcastss->remove($pos);
            if (null === $this->userPodcastssScheduledForDeletion) {
                $this->userPodcastssScheduledForDeletion = clone $this->collUserPodcastss;
                $this->userPodcastssScheduledForDeletion->clear();
            }
            $this->userPodcastssScheduledForDeletion[]= clone $userPodcasts;
            $userPodcasts->setUsers(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related UserPodcastss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|UserPodcasts[] List of UserPodcasts objects
     */
    public function getUserPodcastssJoinPodcasts(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = UserPodcastsQuery::create(null, $criteria);
        $query->joinWith('Podcasts', $joinBehavior);

        return $this->getUserPodcastss($query, $con);
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
     * Gets an array of UserPlaylists objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|UserPlaylists[] List of UserPlaylists objects
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
                $collUserPlaylistss = UserPlaylistsQuery::create(null, $criteria)
                    ->filterByUsers($this)
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
     * Sets a collection of UserPlaylists objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userPlaylistss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setUserPlaylistss(Collection $userPlaylistss, ConnectionInterface $con = null)
    {
        /** @var UserPlaylists[] $userPlaylistssToDelete */
        $userPlaylistssToDelete = $this->getUserPlaylistss(new Criteria(), $con)->diff($userPlaylistss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userPlaylistssScheduledForDeletion = clone $userPlaylistssToDelete;

        foreach ($userPlaylistssToDelete as $userPlaylistsRemoved) {
            $userPlaylistsRemoved->setUsers(null);
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
     * Returns the number of related BaseUserPlaylists objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseUserPlaylists objects.
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

            $query = UserPlaylistsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collUserPlaylistss);
    }

    /**
     * Method called to associate a UserPlaylists object to this object
     * through the UserPlaylists foreign key attribute.
     *
     * @param  UserPlaylists $l UserPlaylists
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function addUserPlaylists(UserPlaylists $l)
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
     * @param UserPlaylists $userPlaylists The UserPlaylists object to add.
     */
    protected function doAddUserPlaylists(UserPlaylists $userPlaylists)
    {
        $this->collUserPlaylistss[]= $userPlaylists;
        $userPlaylists->setUsers($this);
    }

    /**
     * @param  UserPlaylists $userPlaylists The UserPlaylists object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeUserPlaylists(UserPlaylists $userPlaylists)
    {
        if ($this->getUserPlaylistss()->contains($userPlaylists)) {
            $pos = $this->collUserPlaylistss->search($userPlaylists);
            $this->collUserPlaylistss->remove($pos);
            if (null === $this->userPlaylistssScheduledForDeletion) {
                $this->userPlaylistssScheduledForDeletion = clone $this->collUserPlaylistss;
                $this->userPlaylistssScheduledForDeletion->clear();
            }
            $this->userPlaylistssScheduledForDeletion[]= clone $userPlaylists;
            $userPlaylists->setUsers(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related UserPlaylistss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|UserPlaylists[] List of UserPlaylists objects
     */
    public function getUserPlaylistssJoinPlaylists(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = UserPlaylistsQuery::create(null, $criteria);
        $query->joinWith('Playlists', $joinBehavior);

        return $this->getUserPlaylistss($query, $con);
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
     * Gets an array of PlaylistComments objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|PlaylistComments[] List of PlaylistComments objects
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
                $collPlaylistCommentss = PlaylistCommentsQuery::create(null, $criteria)
                    ->filterByUsers($this)
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
     * Sets a collection of PlaylistComments objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $playlistCommentss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setPlaylistCommentss(Collection $playlistCommentss, ConnectionInterface $con = null)
    {
        /** @var PlaylistComments[] $playlistCommentssToDelete */
        $playlistCommentssToDelete = $this->getPlaylistCommentss(new Criteria(), $con)->diff($playlistCommentss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->playlistCommentssScheduledForDeletion = clone $playlistCommentssToDelete;

        foreach ($playlistCommentssToDelete as $playlistCommentsRemoved) {
            $playlistCommentsRemoved->setUsers(null);
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
     * Returns the number of related BasePlaylistComments objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BasePlaylistComments objects.
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

            $query = PlaylistCommentsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collPlaylistCommentss);
    }

    /**
     * Method called to associate a PlaylistComments object to this object
     * through the PlaylistComments foreign key attribute.
     *
     * @param  PlaylistComments $l PlaylistComments
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function addPlaylistComments(PlaylistComments $l)
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
     * @param PlaylistComments $playlistComments The PlaylistComments object to add.
     */
    protected function doAddPlaylistComments(PlaylistComments $playlistComments)
    {
        $this->collPlaylistCommentss[]= $playlistComments;
        $playlistComments->setUsers($this);
    }

    /**
     * @param  PlaylistComments $playlistComments The PlaylistComments object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removePlaylistComments(PlaylistComments $playlistComments)
    {
        if ($this->getPlaylistCommentss()->contains($playlistComments)) {
            $pos = $this->collPlaylistCommentss->search($playlistComments);
            $this->collPlaylistCommentss->remove($pos);
            if (null === $this->playlistCommentssScheduledForDeletion) {
                $this->playlistCommentssScheduledForDeletion = clone $this->collPlaylistCommentss;
                $this->playlistCommentssScheduledForDeletion->clear();
            }
            $this->playlistCommentssScheduledForDeletion[]= clone $playlistComments;
            $playlistComments->setUsers(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related PlaylistCommentss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|PlaylistComments[] List of PlaylistComments objects
     */
    public function getPlaylistCommentssJoinPlaylists(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = PlaylistCommentsQuery::create(null, $criteria);
        $query->joinWith('Playlists', $joinBehavior);

        return $this->getPlaylistCommentss($query, $con);
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
     * Gets an array of UserEpisodes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|UserEpisodes[] List of UserEpisodes objects
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
                $collUserEpisodess = UserEpisodesQuery::create(null, $criteria)
                    ->filterByUser($this)
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
     * Sets a collection of UserEpisodes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userEpisodess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setUserEpisodess(Collection $userEpisodess, ConnectionInterface $con = null)
    {
        /** @var UserEpisodes[] $userEpisodessToDelete */
        $userEpisodessToDelete = $this->getUserEpisodess(new Criteria(), $con)->diff($userEpisodess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userEpisodessScheduledForDeletion = clone $userEpisodessToDelete;

        foreach ($userEpisodessToDelete as $userEpisodesRemoved) {
            $userEpisodesRemoved->setUser(null);
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
     * Returns the number of related BaseUserEpisodes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseUserEpisodes objects.
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

            $query = UserEpisodesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserEpisodess);
    }

    /**
     * Method called to associate a UserEpisodes object to this object
     * through the UserEpisodes foreign key attribute.
     *
     * @param  UserEpisodes $l UserEpisodes
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function addUserEpisodes(UserEpisodes $l)
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
     * @param UserEpisodes $userEpisodes The UserEpisodes object to add.
     */
    protected function doAddUserEpisodes(UserEpisodes $userEpisodes)
    {
        $this->collUserEpisodess[]= $userEpisodes;
        $userEpisodes->setUser($this);
    }

    /**
     * @param  UserEpisodes $userEpisodes The UserEpisodes object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeUserEpisodes(UserEpisodes $userEpisodes)
    {
        if ($this->getUserEpisodess()->contains($userEpisodes)) {
            $pos = $this->collUserEpisodess->search($userEpisodes);
            $this->collUserEpisodess->remove($pos);
            if (null === $this->userEpisodessScheduledForDeletion) {
                $this->userEpisodessScheduledForDeletion = clone $this->collUserEpisodess;
                $this->userEpisodessScheduledForDeletion->clear();
            }
            $this->userEpisodessScheduledForDeletion[]= clone $userEpisodes;
            $userEpisodes->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related UserEpisodess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|UserEpisodes[] List of UserEpisodes objects
     */
    public function getUserEpisodessJoinEpisode(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = UserEpisodesQuery::create(null, $criteria);
        $query->joinWith('Episode', $joinBehavior);

        return $this->getUserEpisodess($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related UserEpisodess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|UserEpisodes[] List of UserEpisodes objects
     */
    public function getUserEpisodessJoinBookmark(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = UserEpisodesQuery::create(null, $criteria);
        $query->joinWith('Bookmark', $joinBehavior);

        return $this->getUserEpisodess($query, $con);
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
     * If this ChildUsers is new, it will return
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
                    ->filterByUsersPlaylistTags($this)
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
     * @return $this|ChildUsers The current object (for fluent API support)
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
            $userPlaylistTagsRemoved->setUsersPlaylistTags(null);
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
                ->filterByUsersPlaylistTags($this)
                ->count($con);
        }

        return count($this->collUserPlaylistTagss);
    }

    /**
     * Method called to associate a UserPlaylistTags object to this object
     * through the UserPlaylistTags foreign key attribute.
     *
     * @param  UserPlaylistTags $l UserPlaylistTags
     * @return $this|\Models\Users\Users The current object (for fluent API support)
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
        $userPlaylistTags->setUsersPlaylistTags($this);
    }

    /**
     * @param  UserPlaylistTags $userPlaylistTags The UserPlaylistTags object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
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
            $userPlaylistTags->setUsersPlaylistTags(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related UserPlaylistTagss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|UserPlaylistTags[] List of UserPlaylistTags objects
     */
    public function getUserPlaylistTagssJoinPlaylistsTags(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = UserPlaylistTagsQuery::create(null, $criteria);
        $query->joinWith('PlaylistsTags', $joinBehavior);

        return $this->getUserPlaylistTagss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related UserPlaylistTagss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
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
     * If this ChildUsers is new, it will return
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
                    ->filterByUsersEpisodeTags($this)
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
     * @return $this|ChildUsers The current object (for fluent API support)
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
            $userEpisodeTagsRemoved->setUsersEpisodeTags(null);
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
                ->filterByUsersEpisodeTags($this)
                ->count($con);
        }

        return count($this->collUserEpisodeTagss);
    }

    /**
     * Method called to associate a UserEpisodeTags object to this object
     * through the UserEpisodeTags foreign key attribute.
     *
     * @param  UserEpisodeTags $l UserEpisodeTags
     * @return $this|\Models\Users\Users The current object (for fluent API support)
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
        $userEpisodeTags->setUsersEpisodeTags($this);
    }

    /**
     * @param  UserEpisodeTags $userEpisodeTags The UserEpisodeTags object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
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
            $userEpisodeTags->setUsersEpisodeTags(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related UserEpisodeTagss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|UserEpisodeTags[] List of UserEpisodeTags objects
     */
    public function getUserEpisodeTagssJoinEpisodesTags(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = UserEpisodeTagsQuery::create(null, $criteria);
        $query->joinWith('EpisodesTags', $joinBehavior);

        return $this->getUserEpisodeTagss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related UserEpisodeTagss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
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
     * Clears out the collLoggings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLoggings()
     */
    public function clearLoggings()
    {
        $this->collLoggings = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLoggings collection loaded partially.
     */
    public function resetPartialLoggings($v = true)
    {
        $this->collLoggingsPartial = $v;
    }

    /**
     * Initializes the collLoggings collection.
     *
     * By default this just sets the collLoggings collection to an empty array (like clearcollLoggings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLoggings($overrideExisting = true)
    {
        if (null !== $this->collLoggings && !$overrideExisting) {
            return;
        }

        $collectionClassName = LoggingTableMap::getTableMap()->getCollectionClassName();

        $this->collLoggings = new $collectionClassName;
        $this->collLoggings->setModel('\Models\Logging\Logging');
    }

    /**
     * Gets an array of Logging objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Logging[] List of Logging objects
     * @throws PropelException
     */
    public function getLoggings(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLoggingsPartial && !$this->isNew();
        if (null === $this->collLoggings || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLoggings) {
                // return empty collection
                $this->initLoggings();
            } else {
                $collLoggings = LoggingQuery::create(null, $criteria)
                    ->filterByUsers($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLoggingsPartial && count($collLoggings)) {
                        $this->initLoggings(false);

                        foreach ($collLoggings as $obj) {
                            if (false == $this->collLoggings->contains($obj)) {
                                $this->collLoggings->append($obj);
                            }
                        }

                        $this->collLoggingsPartial = true;
                    }

                    return $collLoggings;
                }

                if ($partial && $this->collLoggings) {
                    foreach ($this->collLoggings as $obj) {
                        if ($obj->isNew()) {
                            $collLoggings[] = $obj;
                        }
                    }
                }

                $this->collLoggings = $collLoggings;
                $this->collLoggingsPartial = false;
            }
        }

        return $this->collLoggings;
    }

    /**
     * Sets a collection of Logging objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $loggings A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setLoggings(Collection $loggings, ConnectionInterface $con = null)
    {
        /** @var Logging[] $loggingsToDelete */
        $loggingsToDelete = $this->getLoggings(new Criteria(), $con)->diff($loggings);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->loggingsScheduledForDeletion = clone $loggingsToDelete;

        foreach ($loggingsToDelete as $loggingRemoved) {
            $loggingRemoved->setUsers(null);
        }

        $this->collLoggings = null;
        foreach ($loggings as $logging) {
            $this->addLogging($logging);
        }

        $this->collLoggings = $loggings;
        $this->collLoggingsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseLogging objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseLogging objects.
     * @throws PropelException
     */
    public function countLoggings(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLoggingsPartial && !$this->isNew();
        if (null === $this->collLoggings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLoggings) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLoggings());
            }

            $query = LoggingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collLoggings);
    }

    /**
     * Method called to associate a Logging object to this object
     * through the Logging foreign key attribute.
     *
     * @param  Logging $l Logging
     * @return $this|\Models\Users\Users The current object (for fluent API support)
     */
    public function addLogging(Logging $l)
    {
        if ($this->collLoggings === null) {
            $this->initLoggings();
            $this->collLoggingsPartial = true;
        }

        if (!$this->collLoggings->contains($l)) {
            $this->doAddLogging($l);

            if ($this->loggingsScheduledForDeletion and $this->loggingsScheduledForDeletion->contains($l)) {
                $this->loggingsScheduledForDeletion->remove($this->loggingsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Logging $logging The Logging object to add.
     */
    protected function doAddLogging(Logging $logging)
    {
        $this->collLoggings[]= $logging;
        $logging->setUsers($this);
    }

    /**
     * @param  Logging $logging The Logging object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeLogging(Logging $logging)
    {
        if ($this->getLoggings()->contains($logging)) {
            $pos = $this->collLoggings->search($logging);
            $this->collLoggings->remove($pos);
            if (null === $this->loggingsScheduledForDeletion) {
                $this->loggingsScheduledForDeletion = clone $this->collLoggings;
                $this->loggingsScheduledForDeletion->clear();
            }
            $this->loggingsScheduledForDeletion[]= clone $logging;
            $logging->setUsers(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related Loggings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Logging[] List of Logging objects
     */
    public function getLoggingsJoinLogActionTypes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = LoggingQuery::create(null, $criteria);
        $query->joinWith('LogActionTypes', $joinBehavior);

        return $this->getLoggings($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related Loggings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Logging[] List of Logging objects
     */
    public function getLoggingsJoinLogTypes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = LoggingQuery::create(null, $criteria);
        $query->joinWith('LogTypes', $joinBehavior);

        return $this->getLoggings($query, $con);
    }

    /**
     * Clears out the collFollowings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFollowings()
     */
    public function clearFollowings()
    {
        $this->collFollowings = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collFollowings crossRef collection.
     *
     * By default this just sets the collFollowings collection to an empty collection (like clearFollowings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initFollowings()
    {
        $collectionClassName = UserRelationsTableMap::getTableMap()->getCollectionClassName();

        $this->collFollowings = new $collectionClassName;
        $this->collFollowingsPartial = true;
        $this->collFollowings->setModel('\Models\Users\Users');
    }

    /**
     * Checks if the collFollowings collection is loaded.
     *
     * @return bool
     */
    public function isFollowingsLoaded()
    {
        return null !== $this->collFollowings;
    }

    /**
     * Gets a collection of ChildUsers objects related by a many-to-many relationship
     * to the current object by way of the user_relations cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildUsers[] List of ChildUsers objects
     */
    public function getFollowings(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFollowingsPartial && !$this->isNew();
        if (null === $this->collFollowings || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collFollowings) {
                    $this->initFollowings();
                }
            } else {

                $query = ChildUsersQuery::create(null, $criteria)
                    ->filterByFollower($this);
                $collFollowings = $query->find($con);
                if (null !== $criteria) {
                    return $collFollowings;
                }

                if ($partial && $this->collFollowings) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collFollowings as $obj) {
                        if (!$collFollowings->contains($obj)) {
                            $collFollowings[] = $obj;
                        }
                    }
                }

                $this->collFollowings = $collFollowings;
                $this->collFollowingsPartial = false;
            }
        }

        return $this->collFollowings;
    }

    /**
     * Sets a collection of Users objects related by a many-to-many relationship
     * to the current object by way of the user_relations cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $followings A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setFollowings(Collection $followings, ConnectionInterface $con = null)
    {
        $this->clearFollowings();
        $currentFollowings = $this->getFollowings();

        $followingsScheduledForDeletion = $currentFollowings->diff($followings);

        foreach ($followingsScheduledForDeletion as $toDelete) {
            $this->removeFollowing($toDelete);
        }

        foreach ($followings as $following) {
            if (!$currentFollowings->contains($following)) {
                $this->doAddFollowing($following);
            }
        }

        $this->collFollowingsPartial = false;
        $this->collFollowings = $followings;

        return $this;
    }

    /**
     * Gets the number of Users objects related by a many-to-many relationship
     * to the current object by way of the user_relations cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Users objects
     */
    public function countFollowings(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFollowingsPartial && !$this->isNew();
        if (null === $this->collFollowings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFollowings) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getFollowings());
                }

                $query = ChildUsersQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByFollower($this)
                    ->count($con);
            }
        } else {
            return count($this->collFollowings);
        }
    }

    /**
     * Associate a ChildUsers to this object
     * through the user_relations cross reference table.
     *
     * @param ChildUsers $following
     * @return ChildUsers The current object (for fluent API support)
     */
    public function addFollowing(ChildUsers $following)
    {
        if ($this->collFollowings === null) {
            $this->initFollowings();
        }

        if (!$this->getFollowings()->contains($following)) {
            // only add it if the **same** object is not already associated
            $this->collFollowings->push($following);
            $this->doAddFollowing($following);
        }

        return $this;
    }

    /**
     *
     * @param ChildUsers $following
     */
    protected function doAddFollowing(ChildUsers $following)
    {
        $userRelations = new ChildUserRelations();

        $userRelations->setFollowing($following);

        $userRelations->setFollower($this);

        $this->addUserRelationsRelatedByFollowerId($userRelations);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$following->isFollowersLoaded()) {
            $following->initFollowers();
            $following->getFollowers()->push($this);
        } elseif (!$following->getFollowers()->contains($this)) {
            $following->getFollowers()->push($this);
        }

    }

    /**
     * Remove following of this object
     * through the user_relations cross reference table.
     *
     * @param ChildUsers $following
     * @return ChildUsers The current object (for fluent API support)
     */
    public function removeFollowing(ChildUsers $following)
    {
        if ($this->getFollowings()->contains($following)) {
            $userRelations = new ChildUserRelations();
            $userRelations->setFollowing($following);
            if ($following->isFollowersLoaded()) {
                //remove the back reference if available
                $following->getFollowers()->removeObject($this);
            }

            $userRelations->setFollower($this);
            $this->removeUserRelationsRelatedByFollowerId(clone $userRelations);
            $userRelations->clear();

            $this->collFollowings->remove($this->collFollowings->search($following));

            if (null === $this->followingsScheduledForDeletion) {
                $this->followingsScheduledForDeletion = clone $this->collFollowings;
                $this->followingsScheduledForDeletion->clear();
            }

            $this->followingsScheduledForDeletion->push($following);
        }


        return $this;
    }

    /**
     * Clears out the collFollowers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addFollowers()
     */
    public function clearFollowers()
    {
        $this->collFollowers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collFollowers crossRef collection.
     *
     * By default this just sets the collFollowers collection to an empty collection (like clearFollowers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initFollowers()
    {
        $collectionClassName = UserRelationsTableMap::getTableMap()->getCollectionClassName();

        $this->collFollowers = new $collectionClassName;
        $this->collFollowersPartial = true;
        $this->collFollowers->setModel('\Models\Users\Users');
    }

    /**
     * Checks if the collFollowers collection is loaded.
     *
     * @return bool
     */
    public function isFollowersLoaded()
    {
        return null !== $this->collFollowers;
    }

    /**
     * Gets a collection of ChildUsers objects related by a many-to-many relationship
     * to the current object by way of the user_relations cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildUsers[] List of ChildUsers objects
     */
    public function getFollowers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collFollowersPartial && !$this->isNew();
        if (null === $this->collFollowers || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collFollowers) {
                    $this->initFollowers();
                }
            } else {

                $query = ChildUsersQuery::create(null, $criteria)
                    ->filterByFollowing($this);
                $collFollowers = $query->find($con);
                if (null !== $criteria) {
                    return $collFollowers;
                }

                if ($partial && $this->collFollowers) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collFollowers as $obj) {
                        if (!$collFollowers->contains($obj)) {
                            $collFollowers[] = $obj;
                        }
                    }
                }

                $this->collFollowers = $collFollowers;
                $this->collFollowersPartial = false;
            }
        }

        return $this->collFollowers;
    }

    /**
     * Sets a collection of Users objects related by a many-to-many relationship
     * to the current object by way of the user_relations cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $followers A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setFollowers(Collection $followers, ConnectionInterface $con = null)
    {
        $this->clearFollowers();
        $currentFollowers = $this->getFollowers();

        $followersScheduledForDeletion = $currentFollowers->diff($followers);

        foreach ($followersScheduledForDeletion as $toDelete) {
            $this->removeFollower($toDelete);
        }

        foreach ($followers as $follower) {
            if (!$currentFollowers->contains($follower)) {
                $this->doAddFollower($follower);
            }
        }

        $this->collFollowersPartial = false;
        $this->collFollowers = $followers;

        return $this;
    }

    /**
     * Gets the number of Users objects related by a many-to-many relationship
     * to the current object by way of the user_relations cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Users objects
     */
    public function countFollowers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collFollowersPartial && !$this->isNew();
        if (null === $this->collFollowers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFollowers) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getFollowers());
                }

                $query = ChildUsersQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByFollowing($this)
                    ->count($con);
            }
        } else {
            return count($this->collFollowers);
        }
    }

    /**
     * Associate a ChildUsers to this object
     * through the user_relations cross reference table.
     *
     * @param ChildUsers $follower
     * @return ChildUsers The current object (for fluent API support)
     */
    public function addFollower(ChildUsers $follower)
    {
        if ($this->collFollowers === null) {
            $this->initFollowers();
        }

        if (!$this->getFollowers()->contains($follower)) {
            // only add it if the **same** object is not already associated
            $this->collFollowers->push($follower);
            $this->doAddFollower($follower);
        }

        return $this;
    }

    /**
     *
     * @param ChildUsers $follower
     */
    protected function doAddFollower(ChildUsers $follower)
    {
        $userRelations = new ChildUserRelations();

        $userRelations->setFollower($follower);

        $userRelations->setFollowing($this);

        $this->addUserRelationsRelatedByFollowingId($userRelations);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$follower->isFollowingsLoaded()) {
            $follower->initFollowings();
            $follower->getFollowings()->push($this);
        } elseif (!$follower->getFollowings()->contains($this)) {
            $follower->getFollowings()->push($this);
        }

    }

    /**
     * Remove follower of this object
     * through the user_relations cross reference table.
     *
     * @param ChildUsers $follower
     * @return ChildUsers The current object (for fluent API support)
     */
    public function removeFollower(ChildUsers $follower)
    {
        if ($this->getFollowers()->contains($follower)) {
            $userRelations = new ChildUserRelations();
            $userRelations->setFollower($follower);
            if ($follower->isFollowingsLoaded()) {
                //remove the back reference if available
                $follower->getFollowings()->removeObject($this);
            }

            $userRelations->setFollowing($this);
            $this->removeUserRelationsRelatedByFollowingId(clone $userRelations);
            $userRelations->clear();

            $this->collFollowers->remove($this->collFollowers->search($follower));

            if (null === $this->followersScheduledForDeletion) {
                $this->followersScheduledForDeletion = clone $this->collFollowers;
                $this->followersScheduledForDeletion->clear();
            }

            $this->followersScheduledForDeletion->push($follower);
        }


        return $this;
    }

    /**
     * Clears out the collPodcastss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPodcastss()
     */
    public function clearPodcastss()
    {
        $this->collPodcastss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collPodcastss crossRef collection.
     *
     * By default this just sets the collPodcastss collection to an empty collection (like clearPodcastss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPodcastss()
    {
        $collectionClassName = UserPodcastsTableMap::getTableMap()->getCollectionClassName();

        $this->collPodcastss = new $collectionClassName;
        $this->collPodcastssPartial = true;
        $this->collPodcastss->setModel('\Models\Podcasts\Podcasts');
    }

    /**
     * Checks if the collPodcastss collection is loaded.
     *
     * @return bool
     */
    public function isPodcastssLoaded()
    {
        return null !== $this->collPodcastss;
    }

    /**
     * Gets a collection of Podcasts objects related by a many-to-many relationship
     * to the current object by way of the user_podcasts cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|Podcasts[] List of Podcasts objects
     */
    public function getPodcastss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPodcastssPartial && !$this->isNew();
        if (null === $this->collPodcastss || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPodcastss) {
                    $this->initPodcastss();
                }
            } else {

                $query = PodcastsQuery::create(null, $criteria)
                    ->filterByUsers($this);
                $collPodcastss = $query->find($con);
                if (null !== $criteria) {
                    return $collPodcastss;
                }

                if ($partial && $this->collPodcastss) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collPodcastss as $obj) {
                        if (!$collPodcastss->contains($obj)) {
                            $collPodcastss[] = $obj;
                        }
                    }
                }

                $this->collPodcastss = $collPodcastss;
                $this->collPodcastssPartial = false;
            }
        }

        return $this->collPodcastss;
    }

    /**
     * Sets a collection of Podcasts objects related by a many-to-many relationship
     * to the current object by way of the user_podcasts cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $podcastss A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setPodcastss(Collection $podcastss, ConnectionInterface $con = null)
    {
        $this->clearPodcastss();
        $currentPodcastss = $this->getPodcastss();

        $podcastssScheduledForDeletion = $currentPodcastss->diff($podcastss);

        foreach ($podcastssScheduledForDeletion as $toDelete) {
            $this->removePodcasts($toDelete);
        }

        foreach ($podcastss as $podcasts) {
            if (!$currentPodcastss->contains($podcasts)) {
                $this->doAddPodcasts($podcasts);
            }
        }

        $this->collPodcastssPartial = false;
        $this->collPodcastss = $podcastss;

        return $this;
    }

    /**
     * Gets the number of Podcasts objects related by a many-to-many relationship
     * to the current object by way of the user_podcasts cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Podcasts objects
     */
    public function countPodcastss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPodcastssPartial && !$this->isNew();
        if (null === $this->collPodcastss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPodcastss) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getPodcastss());
                }

                $query = PodcastsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUsers($this)
                    ->count($con);
            }
        } else {
            return count($this->collPodcastss);
        }
    }

    /**
     * Associate a Podcasts to this object
     * through the user_podcasts cross reference table.
     *
     * @param Podcasts $podcasts
     * @return ChildUsers The current object (for fluent API support)
     */
    public function addPodcasts(Podcasts $podcasts)
    {
        if ($this->collPodcastss === null) {
            $this->initPodcastss();
        }

        if (!$this->getPodcastss()->contains($podcasts)) {
            // only add it if the **same** object is not already associated
            $this->collPodcastss->push($podcasts);
            $this->doAddPodcasts($podcasts);
        }

        return $this;
    }

    /**
     *
     * @param Podcasts $podcasts
     */
    protected function doAddPodcasts(Podcasts $podcasts)
    {
        $userPodcasts = new UserPodcasts();

        $userPodcasts->setPodcasts($podcasts);

        $userPodcasts->setUsers($this);

        $this->addUserPodcasts($userPodcasts);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$podcasts->isUserssLoaded()) {
            $podcasts->initUserss();
            $podcasts->getUserss()->push($this);
        } elseif (!$podcasts->getUserss()->contains($this)) {
            $podcasts->getUserss()->push($this);
        }

    }

    /**
     * Remove podcasts of this object
     * through the user_podcasts cross reference table.
     *
     * @param Podcasts $podcasts
     * @return ChildUsers The current object (for fluent API support)
     */
    public function removePodcasts(Podcasts $podcasts)
    {
        if ($this->getPodcastss()->contains($podcasts)) {
            $userPodcasts = new UserPodcasts();
            $userPodcasts->setPodcasts($podcasts);
            if ($podcasts->isUserssLoaded()) {
                //remove the back reference if available
                $podcasts->getUserss()->removeObject($this);
            }

            $userPodcasts->setUsers($this);
            $this->removeUserPodcasts(clone $userPodcasts);
            $userPodcasts->clear();

            $this->collPodcastss->remove($this->collPodcastss->search($podcasts));

            if (null === $this->podcastssScheduledForDeletion) {
                $this->podcastssScheduledForDeletion = clone $this->collPodcastss;
                $this->podcastssScheduledForDeletion->clear();
            }

            $this->podcastssScheduledForDeletion->push($podcasts);
        }


        return $this;
    }

    /**
     * Clears out the collPlaylistss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlaylistss()
     */
    public function clearPlaylistss()
    {
        $this->collPlaylistss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collPlaylistss crossRef collection.
     *
     * By default this just sets the collPlaylistss collection to an empty collection (like clearPlaylistss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPlaylistss()
    {
        $collectionClassName = UserPlaylistsTableMap::getTableMap()->getCollectionClassName();

        $this->collPlaylistss = new $collectionClassName;
        $this->collPlaylistssPartial = true;
        $this->collPlaylistss->setModel('\Models\Playlists\Playlists');
    }

    /**
     * Checks if the collPlaylistss collection is loaded.
     *
     * @return bool
     */
    public function isPlaylistssLoaded()
    {
        return null !== $this->collPlaylistss;
    }

    /**
     * Gets a collection of Playlists objects related by a many-to-many relationship
     * to the current object by way of the user_playlists cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|Playlists[] List of Playlists objects
     */
    public function getPlaylistss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistssPartial && !$this->isNew();
        if (null === $this->collPlaylistss || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPlaylistss) {
                    $this->initPlaylistss();
                }
            } else {

                $query = PlaylistsQuery::create(null, $criteria)
                    ->filterByUsers($this);
                $collPlaylistss = $query->find($con);
                if (null !== $criteria) {
                    return $collPlaylistss;
                }

                if ($partial && $this->collPlaylistss) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collPlaylistss as $obj) {
                        if (!$collPlaylistss->contains($obj)) {
                            $collPlaylistss[] = $obj;
                        }
                    }
                }

                $this->collPlaylistss = $collPlaylistss;
                $this->collPlaylistssPartial = false;
            }
        }

        return $this->collPlaylistss;
    }

    /**
     * Sets a collection of Playlists objects related by a many-to-many relationship
     * to the current object by way of the user_playlists cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $playlistss A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setPlaylistss(Collection $playlistss, ConnectionInterface $con = null)
    {
        $this->clearPlaylistss();
        $currentPlaylistss = $this->getPlaylistss();

        $playlistssScheduledForDeletion = $currentPlaylistss->diff($playlistss);

        foreach ($playlistssScheduledForDeletion as $toDelete) {
            $this->removePlaylists($toDelete);
        }

        foreach ($playlistss as $playlists) {
            if (!$currentPlaylistss->contains($playlists)) {
                $this->doAddPlaylists($playlists);
            }
        }

        $this->collPlaylistssPartial = false;
        $this->collPlaylistss = $playlistss;

        return $this;
    }

    /**
     * Gets the number of Playlists objects related by a many-to-many relationship
     * to the current object by way of the user_playlists cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Playlists objects
     */
    public function countPlaylistss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPlaylistssPartial && !$this->isNew();
        if (null === $this->collPlaylistss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPlaylistss) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getPlaylistss());
                }

                $query = PlaylistsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUsers($this)
                    ->count($con);
            }
        } else {
            return count($this->collPlaylistss);
        }
    }

    /**
     * Associate a Playlists to this object
     * through the user_playlists cross reference table.
     *
     * @param Playlists $playlists
     * @return ChildUsers The current object (for fluent API support)
     */
    public function addPlaylists(Playlists $playlists)
    {
        if ($this->collPlaylistss === null) {
            $this->initPlaylistss();
        }

        if (!$this->getPlaylistss()->contains($playlists)) {
            // only add it if the **same** object is not already associated
            $this->collPlaylistss->push($playlists);
            $this->doAddPlaylists($playlists);
        }

        return $this;
    }

    /**
     *
     * @param Playlists $playlists
     */
    protected function doAddPlaylists(Playlists $playlists)
    {
        $userPlaylists = new UserPlaylists();

        $userPlaylists->setPlaylists($playlists);

        $userPlaylists->setUsers($this);

        $this->addUserPlaylists($userPlaylists);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$playlists->isUserssLoaded()) {
            $playlists->initUserss();
            $playlists->getUserss()->push($this);
        } elseif (!$playlists->getUserss()->contains($this)) {
            $playlists->getUserss()->push($this);
        }

    }

    /**
     * Remove playlists of this object
     * through the user_playlists cross reference table.
     *
     * @param Playlists $playlists
     * @return ChildUsers The current object (for fluent API support)
     */
    public function removePlaylists(Playlists $playlists)
    {
        if ($this->getPlaylistss()->contains($playlists)) {
            $userPlaylists = new UserPlaylists();
            $userPlaylists->setPlaylists($playlists);
            if ($playlists->isUserssLoaded()) {
                //remove the back reference if available
                $playlists->getUserss()->removeObject($this);
            }

            $userPlaylists->setUsers($this);
            $this->removeUserPlaylists(clone $userPlaylists);
            $userPlaylists->clear();

            $this->collPlaylistss->remove($this->collPlaylistss->search($playlists));

            if (null === $this->playlistssScheduledForDeletion) {
                $this->playlistssScheduledForDeletion = clone $this->collPlaylistss;
                $this->playlistssScheduledForDeletion->clear();
            }

            $this->playlistssScheduledForDeletion->push($playlists);
        }


        return $this;
    }

    /**
     * Clears out the collPlaylistsTagsPlaylistTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlaylistsTagsPlaylistTags()
     */
    public function clearPlaylistsTagsPlaylistTags()
    {
        $this->collPlaylistsTagsPlaylistTags = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the combinationCollPlaylistsTagsPlaylistTags crossRef collection.
     *
     * By default this just sets the combinationCollPlaylistsTagsPlaylistTags collection to an empty collection (like clearPlaylistsTagsPlaylistTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPlaylistsTagsPlaylistTags()
    {
        $this->combinationCollPlaylistsTagsPlaylistTags = new ObjectCombinationCollection;
        $this->combinationCollPlaylistsTagsPlaylistTagsPartial = true;
    }

    /**
     * Checks if the combinationCollPlaylistsTagsPlaylistTags collection is loaded.
     *
     * @return bool
     */
    public function isPlaylistsTagsPlaylistTagsLoaded()
    {
        return null !== $this->combinationCollPlaylistsTagsPlaylistTags;
    }

    /**
     * Gets a combined collection of Playlists, Tags objects related by a many-to-many relationship
     * to the current object by way of the user_playlist_tags cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCombinationCollection Combination list of Playlists, Tags objects
     */
    public function getPlaylistsTagsPlaylistTags($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollPlaylistsTagsPlaylistTagsPartial && !$this->isNew();
        if (null === $this->combinationCollPlaylistsTagsPlaylistTags || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->combinationCollPlaylistsTagsPlaylistTags) {
                    $this->initPlaylistsTagsPlaylistTags();
                }
            } else {

                $query = UserPlaylistTagsQuery::create(null, $criteria)
                    ->filterByUsersPlaylistTags($this)
                    ->joinPlaylistsTags()
                    ->joinPlaylistTag()
                ;

                $items = $query->find($con);
                $combinationCollPlaylistsTagsPlaylistTags = new ObjectCombinationCollection();
                foreach ($items as $item) {
                    $combination = [];

                    $combination[] = $item->getPlaylistsTags();
                    $combination[] = $item->getPlaylistTag();
                    $combinationCollPlaylistsTagsPlaylistTags[] = $combination;
                }

                if (null !== $criteria) {
                    return $combinationCollPlaylistsTagsPlaylistTags;
                }

                if ($partial && $this->combinationCollPlaylistsTagsPlaylistTags) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->combinationCollPlaylistsTagsPlaylistTags as $obj) {
                        if (!call_user_func_array([$combinationCollPlaylistsTagsPlaylistTags, 'contains'], $obj)) {
                            $combinationCollPlaylistsTagsPlaylistTags[] = $obj;
                        }
                    }
                }

                $this->combinationCollPlaylistsTagsPlaylistTags = $combinationCollPlaylistsTagsPlaylistTags;
                $this->combinationCollPlaylistsTagsPlaylistTagsPartial = false;
            }
        }

        return $this->combinationCollPlaylistsTagsPlaylistTags;
    }

    /**
     * Returns a not cached ObjectCollection of Playlists objects. This will hit always the databases.
     * If you have attached new Playlists object to this object you need to call `save` first to get
     * the correct return value. Use getPlaylistsTagsPlaylistTags() to get the current internal state.
     *
     * @param Tags $playlistTag
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return Playlists[]|ObjectCollection
     */
    public function getPlaylistsTagss(Tags $playlistTag = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createPlaylistsTagssQuery($playlistTag, $criteria)->find($con);
    }

    /**
     * Sets a collection of ChildPlaylists, ChildTags combination objects related by a many-to-many relationship
     * to the current object by way of the user_playlist_tags cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $playlistsTagsPlaylistTags A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setPlaylistsTagsPlaylistTags(Collection $playlistsTagsPlaylistTags, ConnectionInterface $con = null)
    {
        $this->clearPlaylistsTagsPlaylistTags();
        $currentPlaylistsTagsPlaylistTags = $this->getPlaylistsTagsPlaylistTags();

        $combinationCollPlaylistsTagsPlaylistTagsScheduledForDeletion = $currentPlaylistsTagsPlaylistTags->diff($playlistsTagsPlaylistTags);

        foreach ($combinationCollPlaylistsTagsPlaylistTagsScheduledForDeletion as $toDelete) {
            call_user_func_array([$this, 'removePlaylistsTagsPlaylistTag'], $toDelete);
        }

        foreach ($playlistsTagsPlaylistTags as $playlistsTagsPlaylistTag) {
            if (!call_user_func_array([$currentPlaylistsTagsPlaylistTags, 'contains'], $playlistsTagsPlaylistTag)) {
                call_user_func_array([$this, 'doAddPlaylistsTagsPlaylistTag'], $playlistsTagsPlaylistTag);
            }
        }

        $this->combinationCollPlaylistsTagsPlaylistTagsPartial = false;
        $this->combinationCollPlaylistsTagsPlaylistTags = $playlistsTagsPlaylistTags;

        return $this;
    }

    /**
     * Gets the number of ChildPlaylists, ChildTags combination objects related by a many-to-many relationship
     * to the current object by way of the user_playlist_tags cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildPlaylists, ChildTags combination objects
     */
    public function countPlaylistsTagsPlaylistTags(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollPlaylistsTagsPlaylistTagsPartial && !$this->isNew();
        if (null === $this->combinationCollPlaylistsTagsPlaylistTags || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->combinationCollPlaylistsTagsPlaylistTags) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getPlaylistsTagsPlaylistTags());
                }

                $query = UserPlaylistTagsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUsersPlaylistTags($this)
                    ->count($con);
            }
        } else {
            return count($this->combinationCollPlaylistsTagsPlaylistTags);
        }
    }

    /**
     * Returns the not cached count of Playlists objects. This will hit always the databases.
     * If you have attached new Playlists object to this object you need to call `save` first to get
     * the correct return value. Use getPlaylistsTagsPlaylistTags() to get the current internal state.
     *
     * @param Tags $playlistTag
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return integer
     */
    public function countPlaylistsTagss(Tags $playlistTag = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createPlaylistsTagssQuery($playlistTag, $criteria)->count($con);
    }

    /**
     * Associate a Playlists to this object
     * through the user_playlist_tags cross reference table.
     *
     * @param Playlists $playlistsTags,
     * @param Tags $playlistTag
     * @return ChildUsers The current object (for fluent API support)
     */
    public function addPlaylistsTags(Playlists $playlistsTags, Tags $playlistTag)
    {
        if ($this->combinationCollPlaylistsTagsPlaylistTags === null) {
            $this->initPlaylistsTagsPlaylistTags();
        }

        if (!$this->getPlaylistsTagsPlaylistTags()->contains($playlistsTags, $playlistTag)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollPlaylistsTagsPlaylistTags->push($playlistsTags, $playlistTag);
            $this->doAddPlaylistsTagsPlaylistTag($playlistsTags, $playlistTag);
        }

        return $this;
    }

    /**
     * Associate a Tags to this object
     * through the user_playlist_tags cross reference table.
     *
     * @param Tags $playlistTag,
     * @param Playlists $playlistsTags
     * @return ChildUsers The current object (for fluent API support)
     */
    public function addPlaylistTag(Tags $playlistTag, Playlists $playlistsTags)
    {
        if ($this->combinationCollPlaylistsTagsPlaylistTags === null) {
            $this->initPlaylistsTagsPlaylistTags();
        }

        if (!$this->getPlaylistsTagsPlaylistTags()->contains($playlistTag, $playlistsTags)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollPlaylistsTagsPlaylistTags->push($playlistTag, $playlistsTags);
            $this->doAddPlaylistsTagsPlaylistTag($playlistTag, $playlistsTags);
        }

        return $this;
    }

    /**
     *
     * @param Playlists $playlistsTags,
     * @param Tags $playlistTag
     */
    protected function doAddPlaylistsTagsPlaylistTag(Playlists $playlistsTags, Tags $playlistTag)
    {
        $userPlaylistTags = new UserPlaylistTags();

        $userPlaylistTags->setPlaylistsTags($playlistsTags);
        $userPlaylistTags->setPlaylistTag($playlistTag);

        $userPlaylistTags->setUsersPlaylistTags($this);

        $this->addUserPlaylistTags($userPlaylistTags);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($playlistsTags->isPlaylistTagUsersPlaylistTagssLoaded()) {
            $playlistsTags->initPlaylistTagUsersPlaylistTagss();
            $playlistsTags->getPlaylistTagUsersPlaylistTagss()->push($playlistTag, $this);
        } elseif (!$playlistsTags->getPlaylistTagUsersPlaylistTagss()->contains($playlistTag, $this)) {
            $playlistsTags->getPlaylistTagUsersPlaylistTagss()->push($playlistTag, $this);
        }

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($playlistTag->isPlaylistsTagsUsersPlaylistTagssLoaded()) {
            $playlistTag->initPlaylistsTagsUsersPlaylistTagss();
            $playlistTag->getPlaylistsTagsUsersPlaylistTagss()->push($playlistsTags, $this);
        } elseif (!$playlistTag->getPlaylistsTagsUsersPlaylistTagss()->contains($playlistsTags, $this)) {
            $playlistTag->getPlaylistsTagsUsersPlaylistTagss()->push($playlistsTags, $this);
        }

    }

    /**
     * Remove playlistsTags, playlistTag of this object
     * through the user_playlist_tags cross reference table.
     *
     * @param Playlists $playlistsTags,
     * @param Tags $playlistTag
     * @return ChildUsers The current object (for fluent API support)
     */
    public function removePlaylistsTagsPlaylistTag(Playlists $playlistsTags, Tags $playlistTag)
    {
        if ($this->getPlaylistsTagsPlaylistTags()->contains($playlistsTags, $playlistTag)) {
            $userPlaylistTags = new UserPlaylistTags();
            $userPlaylistTags->setPlaylistsTags($playlistsTags);
            if ($playlistsTags->isPlaylistTagUsersPlaylistTagssLoaded()) {
                //remove the back reference if available
                $playlistsTags->getPlaylistTagUsersPlaylistTagss()->removeObject($playlistTag, $this);
            }

            $userPlaylistTags->setPlaylistTag($playlistTag);
            if ($playlistTag->isPlaylistsTagsUsersPlaylistTagssLoaded()) {
                //remove the back reference if available
                $playlistTag->getPlaylistsTagsUsersPlaylistTagss()->removeObject($playlistsTags, $this);
            }

            $userPlaylistTags->setUsersPlaylistTags($this);
            $this->removeUserPlaylistTags(clone $userPlaylistTags);
            $userPlaylistTags->clear();

            $this->combinationCollPlaylistsTagsPlaylistTags->remove($this->combinationCollPlaylistsTagsPlaylistTags->search($playlistsTags, $playlistTag));

            if (null === $this->combinationCollPlaylistsTagsPlaylistTagsScheduledForDeletion) {
                $this->combinationCollPlaylistsTagsPlaylistTagsScheduledForDeletion = clone $this->combinationCollPlaylistsTagsPlaylistTags;
                $this->combinationCollPlaylistsTagsPlaylistTagsScheduledForDeletion->clear();
            }

            $this->combinationCollPlaylistsTagsPlaylistTagsScheduledForDeletion->push($playlistsTags, $playlistTag);
        }


        return $this;
    }

    /**
     * Clears out the collEpisodesTagsEpisodeTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEpisodesTagsEpisodeTags()
     */
    public function clearEpisodesTagsEpisodeTags()
    {
        $this->collEpisodesTagsEpisodeTags = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the combinationCollEpisodesTagsEpisodeTags crossRef collection.
     *
     * By default this just sets the combinationCollEpisodesTagsEpisodeTags collection to an empty collection (like clearEpisodesTagsEpisodeTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initEpisodesTagsEpisodeTags()
    {
        $this->combinationCollEpisodesTagsEpisodeTags = new ObjectCombinationCollection;
        $this->combinationCollEpisodesTagsEpisodeTagsPartial = true;
    }

    /**
     * Checks if the combinationCollEpisodesTagsEpisodeTags collection is loaded.
     *
     * @return bool
     */
    public function isEpisodesTagsEpisodeTagsLoaded()
    {
        return null !== $this->combinationCollEpisodesTagsEpisodeTags;
    }

    /**
     * Gets a combined collection of Episodes, Tags objects related by a many-to-many relationship
     * to the current object by way of the user_episode_tags cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCombinationCollection Combination list of Episodes, Tags objects
     */
    public function getEpisodesTagsEpisodeTags($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollEpisodesTagsEpisodeTagsPartial && !$this->isNew();
        if (null === $this->combinationCollEpisodesTagsEpisodeTags || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->combinationCollEpisodesTagsEpisodeTags) {
                    $this->initEpisodesTagsEpisodeTags();
                }
            } else {

                $query = UserEpisodeTagsQuery::create(null, $criteria)
                    ->filterByUsersEpisodeTags($this)
                    ->joinEpisodesTags()
                    ->joinEpisodeTag()
                ;

                $items = $query->find($con);
                $combinationCollEpisodesTagsEpisodeTags = new ObjectCombinationCollection();
                foreach ($items as $item) {
                    $combination = [];

                    $combination[] = $item->getEpisodesTags();
                    $combination[] = $item->getEpisodeTag();
                    $combinationCollEpisodesTagsEpisodeTags[] = $combination;
                }

                if (null !== $criteria) {
                    return $combinationCollEpisodesTagsEpisodeTags;
                }

                if ($partial && $this->combinationCollEpisodesTagsEpisodeTags) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->combinationCollEpisodesTagsEpisodeTags as $obj) {
                        if (!call_user_func_array([$combinationCollEpisodesTagsEpisodeTags, 'contains'], $obj)) {
                            $combinationCollEpisodesTagsEpisodeTags[] = $obj;
                        }
                    }
                }

                $this->combinationCollEpisodesTagsEpisodeTags = $combinationCollEpisodesTagsEpisodeTags;
                $this->combinationCollEpisodesTagsEpisodeTagsPartial = false;
            }
        }

        return $this->combinationCollEpisodesTagsEpisodeTags;
    }

    /**
     * Returns a not cached ObjectCollection of Episodes objects. This will hit always the databases.
     * If you have attached new Episodes object to this object you need to call `save` first to get
     * the correct return value. Use getEpisodesTagsEpisodeTags() to get the current internal state.
     *
     * @param Tags $episodeTag
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return Episodes[]|ObjectCollection
     */
    public function getEpisodesTagss(Tags $episodeTag = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createEpisodesTagssQuery($episodeTag, $criteria)->find($con);
    }

    /**
     * Sets a collection of ChildEpisodes, ChildTags combination objects related by a many-to-many relationship
     * to the current object by way of the user_episode_tags cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $episodesTagsEpisodeTags A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setEpisodesTagsEpisodeTags(Collection $episodesTagsEpisodeTags, ConnectionInterface $con = null)
    {
        $this->clearEpisodesTagsEpisodeTags();
        $currentEpisodesTagsEpisodeTags = $this->getEpisodesTagsEpisodeTags();

        $combinationCollEpisodesTagsEpisodeTagsScheduledForDeletion = $currentEpisodesTagsEpisodeTags->diff($episodesTagsEpisodeTags);

        foreach ($combinationCollEpisodesTagsEpisodeTagsScheduledForDeletion as $toDelete) {
            call_user_func_array([$this, 'removeEpisodesTagsEpisodeTag'], $toDelete);
        }

        foreach ($episodesTagsEpisodeTags as $episodesTagsEpisodeTag) {
            if (!call_user_func_array([$currentEpisodesTagsEpisodeTags, 'contains'], $episodesTagsEpisodeTag)) {
                call_user_func_array([$this, 'doAddEpisodesTagsEpisodeTag'], $episodesTagsEpisodeTag);
            }
        }

        $this->combinationCollEpisodesTagsEpisodeTagsPartial = false;
        $this->combinationCollEpisodesTagsEpisodeTags = $episodesTagsEpisodeTags;

        return $this;
    }

    /**
     * Gets the number of ChildEpisodes, ChildTags combination objects related by a many-to-many relationship
     * to the current object by way of the user_episode_tags cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildEpisodes, ChildTags combination objects
     */
    public function countEpisodesTagsEpisodeTags(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollEpisodesTagsEpisodeTagsPartial && !$this->isNew();
        if (null === $this->combinationCollEpisodesTagsEpisodeTags || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->combinationCollEpisodesTagsEpisodeTags) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getEpisodesTagsEpisodeTags());
                }

                $query = UserEpisodeTagsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUsersEpisodeTags($this)
                    ->count($con);
            }
        } else {
            return count($this->combinationCollEpisodesTagsEpisodeTags);
        }
    }

    /**
     * Returns the not cached count of Episodes objects. This will hit always the databases.
     * If you have attached new Episodes object to this object you need to call `save` first to get
     * the correct return value. Use getEpisodesTagsEpisodeTags() to get the current internal state.
     *
     * @param Tags $episodeTag
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return integer
     */
    public function countEpisodesTagss(Tags $episodeTag = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createEpisodesTagssQuery($episodeTag, $criteria)->count($con);
    }

    /**
     * Associate a Episodes to this object
     * through the user_episode_tags cross reference table.
     *
     * @param Episodes $episodesTags,
     * @param Tags $episodeTag
     * @return ChildUsers The current object (for fluent API support)
     */
    public function addEpisodesTags(Episodes $episodesTags, Tags $episodeTag)
    {
        if ($this->combinationCollEpisodesTagsEpisodeTags === null) {
            $this->initEpisodesTagsEpisodeTags();
        }

        if (!$this->getEpisodesTagsEpisodeTags()->contains($episodesTags, $episodeTag)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollEpisodesTagsEpisodeTags->push($episodesTags, $episodeTag);
            $this->doAddEpisodesTagsEpisodeTag($episodesTags, $episodeTag);
        }

        return $this;
    }

    /**
     * Associate a Tags to this object
     * through the user_episode_tags cross reference table.
     *
     * @param Tags $episodeTag,
     * @param Episodes $episodesTags
     * @return ChildUsers The current object (for fluent API support)
     */
    public function addEpisodeTag(Tags $episodeTag, Episodes $episodesTags)
    {
        if ($this->combinationCollEpisodesTagsEpisodeTags === null) {
            $this->initEpisodesTagsEpisodeTags();
        }

        if (!$this->getEpisodesTagsEpisodeTags()->contains($episodeTag, $episodesTags)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollEpisodesTagsEpisodeTags->push($episodeTag, $episodesTags);
            $this->doAddEpisodesTagsEpisodeTag($episodeTag, $episodesTags);
        }

        return $this;
    }

    /**
     *
     * @param Episodes $episodesTags,
     * @param Tags $episodeTag
     */
    protected function doAddEpisodesTagsEpisodeTag(Episodes $episodesTags, Tags $episodeTag)
    {
        $userEpisodeTags = new UserEpisodeTags();

        $userEpisodeTags->setEpisodesTags($episodesTags);
        $userEpisodeTags->setEpisodeTag($episodeTag);

        $userEpisodeTags->setUsersEpisodeTags($this);

        $this->addUserEpisodeTags($userEpisodeTags);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($episodesTags->isEpisodeTagUsersEpisodeTagssLoaded()) {
            $episodesTags->initEpisodeTagUsersEpisodeTagss();
            $episodesTags->getEpisodeTagUsersEpisodeTagss()->push($episodeTag, $this);
        } elseif (!$episodesTags->getEpisodeTagUsersEpisodeTagss()->contains($episodeTag, $this)) {
            $episodesTags->getEpisodeTagUsersEpisodeTagss()->push($episodeTag, $this);
        }

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($episodeTag->isEpisodesTagsUsersEpisodeTagssLoaded()) {
            $episodeTag->initEpisodesTagsUsersEpisodeTagss();
            $episodeTag->getEpisodesTagsUsersEpisodeTagss()->push($episodesTags, $this);
        } elseif (!$episodeTag->getEpisodesTagsUsersEpisodeTagss()->contains($episodesTags, $this)) {
            $episodeTag->getEpisodesTagsUsersEpisodeTagss()->push($episodesTags, $this);
        }

    }

    /**
     * Remove episodesTags, episodeTag of this object
     * through the user_episode_tags cross reference table.
     *
     * @param Episodes $episodesTags,
     * @param Tags $episodeTag
     * @return ChildUsers The current object (for fluent API support)
     */
    public function removeEpisodesTagsEpisodeTag(Episodes $episodesTags, Tags $episodeTag)
    {
        if ($this->getEpisodesTagsEpisodeTags()->contains($episodesTags, $episodeTag)) {
            $userEpisodeTags = new UserEpisodeTags();
            $userEpisodeTags->setEpisodesTags($episodesTags);
            if ($episodesTags->isEpisodeTagUsersEpisodeTagssLoaded()) {
                //remove the back reference if available
                $episodesTags->getEpisodeTagUsersEpisodeTagss()->removeObject($episodeTag, $this);
            }

            $userEpisodeTags->setEpisodeTag($episodeTag);
            if ($episodeTag->isEpisodesTagsUsersEpisodeTagssLoaded()) {
                //remove the back reference if available
                $episodeTag->getEpisodesTagsUsersEpisodeTagss()->removeObject($episodesTags, $this);
            }

            $userEpisodeTags->setUsersEpisodeTags($this);
            $this->removeUserEpisodeTags(clone $userEpisodeTags);
            $userEpisodeTags->clear();

            $this->combinationCollEpisodesTagsEpisodeTags->remove($this->combinationCollEpisodesTagsEpisodeTags->search($episodesTags, $episodeTag));

            if (null === $this->combinationCollEpisodesTagsEpisodeTagsScheduledForDeletion) {
                $this->combinationCollEpisodesTagsEpisodeTagsScheduledForDeletion = clone $this->combinationCollEpisodesTagsEpisodeTags;
                $this->combinationCollEpisodesTagsEpisodeTagsScheduledForDeletion->clear();
            }

            $this->combinationCollEpisodesTagsEpisodeTagsScheduledForDeletion->push($episodesTags, $episodeTag);
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
        $this->email = null;
        $this->first_name = null;
        $this->last_name = null;
        $this->active = null;
        $this->google_id = null;
        $this->image_url = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->collUserSessionss) {
                foreach ($this->collUserSessionss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserRelationssRelatedByFollowerId) {
                foreach ($this->collUserRelationssRelatedByFollowerId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserRelationssRelatedByFollowingId) {
                foreach ($this->collUserRelationssRelatedByFollowingId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAudioPlayerSettingss) {
                foreach ($this->collAudioPlayerSettingss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserSettingss) {
                foreach ($this->collUserSettingss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserPodcastss) {
                foreach ($this->collUserPodcastss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserPlaylistss) {
                foreach ($this->collUserPlaylistss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlaylistCommentss) {
                foreach ($this->collPlaylistCommentss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserEpisodess) {
                foreach ($this->collUserEpisodess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserPlaylistTagss) {
                foreach ($this->collUserPlaylistTagss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserEpisodeTagss) {
                foreach ($this->collUserEpisodeTagss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLoggings) {
                foreach ($this->collLoggings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFollowings) {
                foreach ($this->collFollowings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFollowers) {
                foreach ($this->collFollowers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPodcastss) {
                foreach ($this->collPodcastss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPlaylistss) {
                foreach ($this->collPlaylistss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->combinationCollPlaylistsTagsPlaylistTags) {
                foreach ($this->combinationCollPlaylistsTagsPlaylistTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->combinationCollEpisodesTagsEpisodeTags) {
                foreach ($this->combinationCollEpisodesTagsEpisodeTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collUserSessionss = null;
        $this->collUserRelationssRelatedByFollowerId = null;
        $this->collUserRelationssRelatedByFollowingId = null;
        $this->collAudioPlayerSettingss = null;
        $this->collUserSettingss = null;
        $this->collUserPodcastss = null;
        $this->collUserPlaylistss = null;
        $this->collPlaylistCommentss = null;
        $this->collUserEpisodess = null;
        $this->collUserPlaylistTagss = null;
        $this->collUserEpisodeTagss = null;
        $this->collLoggings = null;
        $this->collFollowings = null;
        $this->collFollowers = null;
        $this->collPodcastss = null;
        $this->collPlaylistss = null;
        $this->combinationCollPlaylistsTagsPlaylistTags = null;
        $this->combinationCollEpisodesTagsEpisodeTags = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UsersTableMap::DEFAULT_STRING_FORMAT);
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
