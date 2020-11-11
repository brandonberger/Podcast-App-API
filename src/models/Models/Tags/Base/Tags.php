<?php

namespace Models\Tags\Base;

use \DateTime;
use \Exception;
use \PDO;
use Models\Episodes\Episodes;
use Models\Playlists\Playlists;
use Models\Tags\Tags as ChildTags;
use Models\Tags\TagsQuery as ChildTagsQuery;
use Models\Tags\Map\TagsTableMap;
use Models\UserTags\UserEpisodeTags;
use Models\UserTags\UserEpisodeTagsQuery;
use Models\UserTags\UserPlaylistTags;
use Models\UserTags\UserPlaylistTagsQuery;
use Models\UserTags\Base\UserEpisodeTags as BaseUserEpisodeTags;
use Models\UserTags\Base\UserPlaylistTags as BaseUserPlaylistTags;
use Models\UserTags\Map\UserEpisodeTagsTableMap;
use Models\UserTags\Map\UserPlaylistTagsTableMap;
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
 * Base class that represents a row from the 'tags' table.
 *
 *
 *
 * @package    propel.generator.Models.Tags.Base
 */
abstract class Tags implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Tags\\Map\\TagsTableMap';


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
     * The value for the super_tag field.
     *
     * @var        int
     */
    protected $super_tag;

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
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildPlaylists, ChildUsers combination combinations.
     */
    protected $combinationCollPlaylistsTagsUsersPlaylistTagss;

    /**
     * @var bool
     */
    protected $combinationCollPlaylistsTagsUsersPlaylistTagssPartial;

    /**
     * @var        ObjectCollection|Playlists[] Cross Collection to store aggregation of Playlists objects.
     */
    protected $collPlaylistsTagss;

    /**
     * @var bool
     */
    protected $collPlaylistsTagssPartial;

    /**
     * @var        ObjectCollection|Users[] Cross Collection to store aggregation of Users objects.
     */
    protected $collUsersPlaylistTagss;

    /**
     * @var bool
     */
    protected $collUsersPlaylistTagssPartial;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildEpisodes, ChildUsers combination combinations.
     */
    protected $combinationCollEpisodesTagsUsersEpisodeTagss;

    /**
     * @var bool
     */
    protected $combinationCollEpisodesTagsUsersEpisodeTagssPartial;

    /**
     * @var        ObjectCollection|Episodes[] Cross Collection to store aggregation of Episodes objects.
     */
    protected $collEpisodesTagss;

    /**
     * @var bool
     */
    protected $collEpisodesTagssPartial;

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
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildPlaylists, ChildUsers combination combinations.
     */
    protected $combinationCollPlaylistsTagsUsersPlaylistTagssScheduledForDeletion = null;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildEpisodes, ChildUsers combination combinations.
     */
    protected $combinationCollEpisodesTagsUsersEpisodeTagssScheduledForDeletion = null;

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
     * Initializes internal state of Models\Tags\Base\Tags object.
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
     * Compares this with another <code>Tags</code> instance.  If
     * <code>obj</code> is an instance of <code>Tags</code>, delegates to
     * <code>equals(Tags)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Tags The current object, for fluid interface
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
     * Get the [super_tag] column value.
     *
     * @return int
     */
    public function getSuperTag()
    {
        return $this->super_tag;
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
     * @return $this|\Models\Tags\Tags The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[TagsTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Models\Tags\Tags The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[TagsTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [super_tag] column.
     *
     * @param int $v new value
     * @return $this|\Models\Tags\Tags The current object (for fluent API support)
     */
    public function setSuperTag($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->super_tag !== $v) {
            $this->super_tag = $v;
            $this->modifiedColumns[TagsTableMap::COL_SUPER_TAG] = true;
        }

        return $this;
    } // setSuperTag()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Tags\Tags The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[TagsTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Tags\Tags The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[TagsTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : TagsTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : TagsTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : TagsTableMap::translateFieldName('SuperTag', TableMap::TYPE_PHPNAME, $indexType)];
            $this->super_tag = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : TagsTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : TagsTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = TagsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Tags\\Tags'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(TagsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildTagsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collUserPlaylistTagss = null;

            $this->collUserEpisodeTagss = null;

            $this->collPlaylistsTagsUsersPlaylistTagss = null;
            $this->collEpisodesTagsUsersEpisodeTagss = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Tags::setDeleted()
     * @see Tags::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(TagsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildTagsQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(TagsTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(TagsTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(TagsTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(TagsTableMap::COL_UPDATED_AT)) {
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
                TagsTableMap::addInstanceToPool($this);
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

            if ($this->combinationCollPlaylistsTagsUsersPlaylistTagssScheduledForDeletion !== null) {
                if (!$this->combinationCollPlaylistsTagsUsersPlaylistTagssScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->combinationCollPlaylistsTagsUsersPlaylistTagssScheduledForDeletion as $combination) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $combination[0]->getId();
                        $entryPk[2] = $combination[1]->getId();

                        $pks[] = $entryPk;
                    }

                    \Models\UserTags\UserPlaylistTagsQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->combinationCollPlaylistsTagsUsersPlaylistTagssScheduledForDeletion = null;
                }

            }

            if (null !== $this->combinationCollPlaylistsTagsUsersPlaylistTagss) {
                foreach ($this->combinationCollPlaylistsTagsUsersPlaylistTagss as $combination) {

                    //$combination[0] = Playlists (user_playlist_tags_fk_e258c7)
                    if (!$combination[0]->isDeleted() && ($combination[0]->isNew() || $combination[0]->isModified())) {
                        $combination[0]->save($con);
                    }

                    //$combination[1] = Users (user_playlist_tags_fk_69bd79)
                    if (!$combination[1]->isDeleted() && ($combination[1]->isNew() || $combination[1]->isModified())) {
                        $combination[1]->save($con);
                    }

                }
            }


            if ($this->combinationCollEpisodesTagsUsersEpisodeTagssScheduledForDeletion !== null) {
                if (!$this->combinationCollEpisodesTagsUsersEpisodeTagssScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->combinationCollEpisodesTagsUsersEpisodeTagssScheduledForDeletion as $combination) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $combination[0]->getId();
                        $entryPk[2] = $combination[1]->getId();

                        $pks[] = $entryPk;
                    }

                    \Models\UserTags\UserEpisodeTagsQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->combinationCollEpisodesTagsUsersEpisodeTagssScheduledForDeletion = null;
                }

            }

            if (null !== $this->combinationCollEpisodesTagsUsersEpisodeTagss) {
                foreach ($this->combinationCollEpisodesTagsUsersEpisodeTagss as $combination) {

                    //$combination[0] = Episodes (user_episode_tags_fk_4e8703)
                    if (!$combination[0]->isDeleted() && ($combination[0]->isNew() || $combination[0]->isModified())) {
                        $combination[0]->save($con);
                    }

                    //$combination[1] = Users (user_episode_tags_fk_69bd79)
                    if (!$combination[1]->isDeleted() && ($combination[1]->isNew() || $combination[1]->isModified())) {
                        $combination[1]->save($con);
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

        $this->modifiedColumns[TagsTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . TagsTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(TagsTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(TagsTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(TagsTableMap::COL_SUPER_TAG)) {
            $modifiedColumns[':p' . $index++]  = 'super_tag';
        }
        if ($this->isColumnModified(TagsTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(TagsTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO tags (%s) VALUES (%s)',
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
                    case 'super_tag':
                        $stmt->bindValue($identifier, $this->super_tag, PDO::PARAM_INT);
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
        $pos = TagsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getSuperTag();
                break;
            case 3:
                return $this->getCreatedAt();
                break;
            case 4:
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

        if (isset($alreadyDumpedObjects['Tags'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Tags'][$this->hashCode()] = true;
        $keys = TagsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getSuperTag(),
            $keys[3] => $this->getCreatedAt(),
            $keys[4] => $this->getUpdatedAt(),
        );
        if ($result[$keys[3]] instanceof \DateTimeInterface) {
            $result[$keys[3]] = $result[$keys[3]]->format('c');
        }

        if ($result[$keys[4]] instanceof \DateTimeInterface) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
     * @return $this|\Models\Tags\Tags
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = TagsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\Tags\Tags
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
                $this->setSuperTag($value);
                break;
            case 3:
                $this->setCreatedAt($value);
                break;
            case 4:
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
        $keys = TagsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setSuperTag($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setCreatedAt($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setUpdatedAt($arr[$keys[4]]);
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
     * @return $this|\Models\Tags\Tags The current object, for fluid interface
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
        $criteria = new Criteria(TagsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(TagsTableMap::COL_ID)) {
            $criteria->add(TagsTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(TagsTableMap::COL_NAME)) {
            $criteria->add(TagsTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(TagsTableMap::COL_SUPER_TAG)) {
            $criteria->add(TagsTableMap::COL_SUPER_TAG, $this->super_tag);
        }
        if ($this->isColumnModified(TagsTableMap::COL_CREATED_AT)) {
            $criteria->add(TagsTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(TagsTableMap::COL_UPDATED_AT)) {
            $criteria->add(TagsTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildTagsQuery::create();
        $criteria->add(TagsTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Models\Tags\Tags (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setSuperTag($this->getSuperTag());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

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
     * @return \Models\Tags\Tags Clone of current object.
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
        if ('UserPlaylistTags' == $relationName) {
            $this->initUserPlaylistTagss();
            return;
        }
        if ('UserEpisodeTags' == $relationName) {
            $this->initUserEpisodeTagss();
            return;
        }
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
     * If this ChildTags is new, it will return
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
                    ->filterByPlaylistTag($this)
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
     * @return $this|ChildTags The current object (for fluent API support)
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
            $userPlaylistTagsRemoved->setPlaylistTag(null);
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
                ->filterByPlaylistTag($this)
                ->count($con);
        }

        return count($this->collUserPlaylistTagss);
    }

    /**
     * Method called to associate a UserPlaylistTags object to this object
     * through the UserPlaylistTags foreign key attribute.
     *
     * @param  UserPlaylistTags $l UserPlaylistTags
     * @return $this|\Models\Tags\Tags The current object (for fluent API support)
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
        $userPlaylistTags->setPlaylistTag($this);
    }

    /**
     * @param  UserPlaylistTags $userPlaylistTags The UserPlaylistTags object to remove.
     * @return $this|ChildTags The current object (for fluent API support)
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
            $userPlaylistTags->setPlaylistTag(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Tags is new, it will return
     * an empty collection; or if this Tags has previously
     * been saved, it will retrieve related UserPlaylistTagss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Tags.
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
     * Otherwise if this Tags is new, it will return
     * an empty collection; or if this Tags has previously
     * been saved, it will retrieve related UserPlaylistTagss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Tags.
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
     * If this ChildTags is new, it will return
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
                    ->filterByEpisodeTag($this)
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
     * @return $this|ChildTags The current object (for fluent API support)
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
            $userEpisodeTagsRemoved->setEpisodeTag(null);
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
                ->filterByEpisodeTag($this)
                ->count($con);
        }

        return count($this->collUserEpisodeTagss);
    }

    /**
     * Method called to associate a UserEpisodeTags object to this object
     * through the UserEpisodeTags foreign key attribute.
     *
     * @param  UserEpisodeTags $l UserEpisodeTags
     * @return $this|\Models\Tags\Tags The current object (for fluent API support)
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
        $userEpisodeTags->setEpisodeTag($this);
    }

    /**
     * @param  UserEpisodeTags $userEpisodeTags The UserEpisodeTags object to remove.
     * @return $this|ChildTags The current object (for fluent API support)
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
            $userEpisodeTags->setEpisodeTag(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Tags is new, it will return
     * an empty collection; or if this Tags has previously
     * been saved, it will retrieve related UserEpisodeTagss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Tags.
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
     * Otherwise if this Tags is new, it will return
     * an empty collection; or if this Tags has previously
     * been saved, it will retrieve related UserEpisodeTagss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Tags.
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
     * Clears out the collPlaylistsTagsUsersPlaylistTagss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPlaylistsTagsUsersPlaylistTagss()
     */
    public function clearPlaylistsTagsUsersPlaylistTagss()
    {
        $this->collPlaylistsTagsUsersPlaylistTagss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the combinationCollPlaylistsTagsUsersPlaylistTagss crossRef collection.
     *
     * By default this just sets the combinationCollPlaylistsTagsUsersPlaylistTagss collection to an empty collection (like clearPlaylistsTagsUsersPlaylistTagss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPlaylistsTagsUsersPlaylistTagss()
    {
        $this->combinationCollPlaylistsTagsUsersPlaylistTagss = new ObjectCombinationCollection;
        $this->combinationCollPlaylistsTagsUsersPlaylistTagssPartial = true;
    }

    /**
     * Checks if the combinationCollPlaylistsTagsUsersPlaylistTagss collection is loaded.
     *
     * @return bool
     */
    public function isPlaylistsTagsUsersPlaylistTagssLoaded()
    {
        return null !== $this->combinationCollPlaylistsTagsUsersPlaylistTagss;
    }

    /**
     * Gets a combined collection of Playlists, Users objects related by a many-to-many relationship
     * to the current object by way of the user_playlist_tags cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTags is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCombinationCollection Combination list of Playlists, Users objects
     */
    public function getPlaylistsTagsUsersPlaylistTagss($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollPlaylistsTagsUsersPlaylistTagssPartial && !$this->isNew();
        if (null === $this->combinationCollPlaylistsTagsUsersPlaylistTagss || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->combinationCollPlaylistsTagsUsersPlaylistTagss) {
                    $this->initPlaylistsTagsUsersPlaylistTagss();
                }
            } else {

                $query = UserPlaylistTagsQuery::create(null, $criteria)
                    ->filterByPlaylistTag($this)
                    ->joinPlaylistsTags()
                    ->joinUsersPlaylistTags()
                ;

                $items = $query->find($con);
                $combinationCollPlaylistsTagsUsersPlaylistTagss = new ObjectCombinationCollection();
                foreach ($items as $item) {
                    $combination = [];

                    $combination[] = $item->getPlaylistsTags();
                    $combination[] = $item->getUsersPlaylistTags();
                    $combinationCollPlaylistsTagsUsersPlaylistTagss[] = $combination;
                }

                if (null !== $criteria) {
                    return $combinationCollPlaylistsTagsUsersPlaylistTagss;
                }

                if ($partial && $this->combinationCollPlaylistsTagsUsersPlaylistTagss) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->combinationCollPlaylistsTagsUsersPlaylistTagss as $obj) {
                        if (!call_user_func_array([$combinationCollPlaylistsTagsUsersPlaylistTagss, 'contains'], $obj)) {
                            $combinationCollPlaylistsTagsUsersPlaylistTagss[] = $obj;
                        }
                    }
                }

                $this->combinationCollPlaylistsTagsUsersPlaylistTagss = $combinationCollPlaylistsTagsUsersPlaylistTagss;
                $this->combinationCollPlaylistsTagsUsersPlaylistTagssPartial = false;
            }
        }

        return $this->combinationCollPlaylistsTagsUsersPlaylistTagss;
    }

    /**
     * Returns a not cached ObjectCollection of Playlists objects. This will hit always the databases.
     * If you have attached new Playlists object to this object you need to call `save` first to get
     * the correct return value. Use getPlaylistsTagsUsersPlaylistTagss() to get the current internal state.
     *
     * @param Users $usersPlaylistTags
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return Playlists[]|ObjectCollection
     */
    public function getPlaylistsTagss(Users $usersPlaylistTags = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createPlaylistsTagssQuery($usersPlaylistTags, $criteria)->find($con);
    }

    /**
     * Sets a collection of ChildPlaylists, ChildUsers combination objects related by a many-to-many relationship
     * to the current object by way of the user_playlist_tags cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $playlistsTagsUsersPlaylistTagss A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildTags The current object (for fluent API support)
     */
    public function setPlaylistsTagsUsersPlaylistTagss(Collection $playlistsTagsUsersPlaylistTagss, ConnectionInterface $con = null)
    {
        $this->clearPlaylistsTagsUsersPlaylistTagss();
        $currentPlaylistsTagsUsersPlaylistTagss = $this->getPlaylistsTagsUsersPlaylistTagss();

        $combinationCollPlaylistsTagsUsersPlaylistTagssScheduledForDeletion = $currentPlaylistsTagsUsersPlaylistTagss->diff($playlistsTagsUsersPlaylistTagss);

        foreach ($combinationCollPlaylistsTagsUsersPlaylistTagssScheduledForDeletion as $toDelete) {
            call_user_func_array([$this, 'removePlaylistsTagsUsersPlaylistTags'], $toDelete);
        }

        foreach ($playlistsTagsUsersPlaylistTagss as $playlistsTagsUsersPlaylistTags) {
            if (!call_user_func_array([$currentPlaylistsTagsUsersPlaylistTagss, 'contains'], $playlistsTagsUsersPlaylistTags)) {
                call_user_func_array([$this, 'doAddPlaylistsTagsUsersPlaylistTags'], $playlistsTagsUsersPlaylistTags);
            }
        }

        $this->combinationCollPlaylistsTagsUsersPlaylistTagssPartial = false;
        $this->combinationCollPlaylistsTagsUsersPlaylistTagss = $playlistsTagsUsersPlaylistTagss;

        return $this;
    }

    /**
     * Gets the number of ChildPlaylists, ChildUsers combination objects related by a many-to-many relationship
     * to the current object by way of the user_playlist_tags cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildPlaylists, ChildUsers combination objects
     */
    public function countPlaylistsTagsUsersPlaylistTagss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollPlaylistsTagsUsersPlaylistTagssPartial && !$this->isNew();
        if (null === $this->combinationCollPlaylistsTagsUsersPlaylistTagss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->combinationCollPlaylistsTagsUsersPlaylistTagss) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getPlaylistsTagsUsersPlaylistTagss());
                }

                $query = UserPlaylistTagsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPlaylistTag($this)
                    ->count($con);
            }
        } else {
            return count($this->combinationCollPlaylistsTagsUsersPlaylistTagss);
        }
    }

    /**
     * Returns the not cached count of Playlists objects. This will hit always the databases.
     * If you have attached new Playlists object to this object you need to call `save` first to get
     * the correct return value. Use getPlaylistsTagsUsersPlaylistTagss() to get the current internal state.
     *
     * @param Users $usersPlaylistTags
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return integer
     */
    public function countPlaylistsTagss(Users $usersPlaylistTags = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createPlaylistsTagssQuery($usersPlaylistTags, $criteria)->count($con);
    }

    /**
     * Associate a Playlists to this object
     * through the user_playlist_tags cross reference table.
     *
     * @param Playlists $playlistsTags,
     * @param Users $usersPlaylistTags
     * @return ChildTags The current object (for fluent API support)
     */
    public function addPlaylistsTags(Playlists $playlistsTags, Users $usersPlaylistTags)
    {
        if ($this->combinationCollPlaylistsTagsUsersPlaylistTagss === null) {
            $this->initPlaylistsTagsUsersPlaylistTagss();
        }

        if (!$this->getPlaylistsTagsUsersPlaylistTagss()->contains($playlistsTags, $usersPlaylistTags)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollPlaylistsTagsUsersPlaylistTagss->push($playlistsTags, $usersPlaylistTags);
            $this->doAddPlaylistsTagsUsersPlaylistTags($playlistsTags, $usersPlaylistTags);
        }

        return $this;
    }

    /**
     * Associate a Users to this object
     * through the user_playlist_tags cross reference table.
     *
     * @param Users $usersPlaylistTags,
     * @param Playlists $playlistsTags
     * @return ChildTags The current object (for fluent API support)
     */
    public function addUsersPlaylistTags(Users $usersPlaylistTags, Playlists $playlistsTags)
    {
        if ($this->combinationCollPlaylistsTagsUsersPlaylistTagss === null) {
            $this->initPlaylistsTagsUsersPlaylistTagss();
        }

        if (!$this->getPlaylistsTagsUsersPlaylistTagss()->contains($usersPlaylistTags, $playlistsTags)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollPlaylistsTagsUsersPlaylistTagss->push($usersPlaylistTags, $playlistsTags);
            $this->doAddPlaylistsTagsUsersPlaylistTags($usersPlaylistTags, $playlistsTags);
        }

        return $this;
    }

    /**
     *
     * @param Playlists $playlistsTags,
     * @param Users $usersPlaylistTags
     */
    protected function doAddPlaylistsTagsUsersPlaylistTags(Playlists $playlistsTags, Users $usersPlaylistTags)
    {
        $userPlaylistTags = new UserPlaylistTags();

        $userPlaylistTags->setPlaylistsTags($playlistsTags);
        $userPlaylistTags->setUsersPlaylistTags($usersPlaylistTags);

        $userPlaylistTags->setPlaylistTag($this);

        $this->addUserPlaylistTags($userPlaylistTags);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($playlistsTags->isPlaylistTagUsersPlaylistTagssLoaded()) {
            $playlistsTags->initPlaylistTagUsersPlaylistTagss();
            $playlistsTags->getPlaylistTagUsersPlaylistTagss()->push($this, $usersPlaylistTags);
        } elseif (!$playlistsTags->getPlaylistTagUsersPlaylistTagss()->contains($this, $usersPlaylistTags)) {
            $playlistsTags->getPlaylistTagUsersPlaylistTagss()->push($this, $usersPlaylistTags);
        }

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($usersPlaylistTags->isPlaylistsTagsPlaylistTagsLoaded()) {
            $usersPlaylistTags->initPlaylistsTagsPlaylistTags();
            $usersPlaylistTags->getPlaylistsTagsPlaylistTags()->push($playlistsTags, $this);
        } elseif (!$usersPlaylistTags->getPlaylistsTagsPlaylistTags()->contains($playlistsTags, $this)) {
            $usersPlaylistTags->getPlaylistsTagsPlaylistTags()->push($playlistsTags, $this);
        }

    }

    /**
     * Remove playlistsTags, usersPlaylistTags of this object
     * through the user_playlist_tags cross reference table.
     *
     * @param Playlists $playlistsTags,
     * @param Users $usersPlaylistTags
     * @return ChildTags The current object (for fluent API support)
     */
    public function removePlaylistsTagsUsersPlaylistTags(Playlists $playlistsTags, Users $usersPlaylistTags)
    {
        if ($this->getPlaylistsTagsUsersPlaylistTagss()->contains($playlistsTags, $usersPlaylistTags)) {
            $userPlaylistTags = new UserPlaylistTags();
            $userPlaylistTags->setPlaylistsTags($playlistsTags);
            if ($playlistsTags->isPlaylistTagUsersPlaylistTagssLoaded()) {
                //remove the back reference if available
                $playlistsTags->getPlaylistTagUsersPlaylistTagss()->removeObject($this, $usersPlaylistTags);
            }

            $userPlaylistTags->setUsersPlaylistTags($usersPlaylistTags);
            if ($usersPlaylistTags->isPlaylistsTagsPlaylistTagsLoaded()) {
                //remove the back reference if available
                $usersPlaylistTags->getPlaylistsTagsPlaylistTags()->removeObject($playlistsTags, $this);
            }

            $userPlaylistTags->setPlaylistTag($this);
            $this->removeUserPlaylistTags(clone $userPlaylistTags);
            $userPlaylistTags->clear();

            $this->combinationCollPlaylistsTagsUsersPlaylistTagss->remove($this->combinationCollPlaylistsTagsUsersPlaylistTagss->search($playlistsTags, $usersPlaylistTags));

            if (null === $this->combinationCollPlaylistsTagsUsersPlaylistTagssScheduledForDeletion) {
                $this->combinationCollPlaylistsTagsUsersPlaylistTagssScheduledForDeletion = clone $this->combinationCollPlaylistsTagsUsersPlaylistTagss;
                $this->combinationCollPlaylistsTagsUsersPlaylistTagssScheduledForDeletion->clear();
            }

            $this->combinationCollPlaylistsTagsUsersPlaylistTagssScheduledForDeletion->push($playlistsTags, $usersPlaylistTags);
        }


        return $this;
    }

    /**
     * Clears out the collEpisodesTagsUsersEpisodeTagss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEpisodesTagsUsersEpisodeTagss()
     */
    public function clearEpisodesTagsUsersEpisodeTagss()
    {
        $this->collEpisodesTagsUsersEpisodeTagss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the combinationCollEpisodesTagsUsersEpisodeTagss crossRef collection.
     *
     * By default this just sets the combinationCollEpisodesTagsUsersEpisodeTagss collection to an empty collection (like clearEpisodesTagsUsersEpisodeTagss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initEpisodesTagsUsersEpisodeTagss()
    {
        $this->combinationCollEpisodesTagsUsersEpisodeTagss = new ObjectCombinationCollection;
        $this->combinationCollEpisodesTagsUsersEpisodeTagssPartial = true;
    }

    /**
     * Checks if the combinationCollEpisodesTagsUsersEpisodeTagss collection is loaded.
     *
     * @return bool
     */
    public function isEpisodesTagsUsersEpisodeTagssLoaded()
    {
        return null !== $this->combinationCollEpisodesTagsUsersEpisodeTagss;
    }

    /**
     * Gets a combined collection of Episodes, Users objects related by a many-to-many relationship
     * to the current object by way of the user_episode_tags cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTags is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCombinationCollection Combination list of Episodes, Users objects
     */
    public function getEpisodesTagsUsersEpisodeTagss($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollEpisodesTagsUsersEpisodeTagssPartial && !$this->isNew();
        if (null === $this->combinationCollEpisodesTagsUsersEpisodeTagss || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->combinationCollEpisodesTagsUsersEpisodeTagss) {
                    $this->initEpisodesTagsUsersEpisodeTagss();
                }
            } else {

                $query = UserEpisodeTagsQuery::create(null, $criteria)
                    ->filterByEpisodeTag($this)
                    ->joinEpisodesTags()
                    ->joinUsersEpisodeTags()
                ;

                $items = $query->find($con);
                $combinationCollEpisodesTagsUsersEpisodeTagss = new ObjectCombinationCollection();
                foreach ($items as $item) {
                    $combination = [];

                    $combination[] = $item->getEpisodesTags();
                    $combination[] = $item->getUsersEpisodeTags();
                    $combinationCollEpisodesTagsUsersEpisodeTagss[] = $combination;
                }

                if (null !== $criteria) {
                    return $combinationCollEpisodesTagsUsersEpisodeTagss;
                }

                if ($partial && $this->combinationCollEpisodesTagsUsersEpisodeTagss) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->combinationCollEpisodesTagsUsersEpisodeTagss as $obj) {
                        if (!call_user_func_array([$combinationCollEpisodesTagsUsersEpisodeTagss, 'contains'], $obj)) {
                            $combinationCollEpisodesTagsUsersEpisodeTagss[] = $obj;
                        }
                    }
                }

                $this->combinationCollEpisodesTagsUsersEpisodeTagss = $combinationCollEpisodesTagsUsersEpisodeTagss;
                $this->combinationCollEpisodesTagsUsersEpisodeTagssPartial = false;
            }
        }

        return $this->combinationCollEpisodesTagsUsersEpisodeTagss;
    }

    /**
     * Returns a not cached ObjectCollection of Episodes objects. This will hit always the databases.
     * If you have attached new Episodes object to this object you need to call `save` first to get
     * the correct return value. Use getEpisodesTagsUsersEpisodeTagss() to get the current internal state.
     *
     * @param Users $usersEpisodeTags
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return Episodes[]|ObjectCollection
     */
    public function getEpisodesTagss(Users $usersEpisodeTags = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createEpisodesTagssQuery($usersEpisodeTags, $criteria)->find($con);
    }

    /**
     * Sets a collection of ChildEpisodes, ChildUsers combination objects related by a many-to-many relationship
     * to the current object by way of the user_episode_tags cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $episodesTagsUsersEpisodeTagss A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildTags The current object (for fluent API support)
     */
    public function setEpisodesTagsUsersEpisodeTagss(Collection $episodesTagsUsersEpisodeTagss, ConnectionInterface $con = null)
    {
        $this->clearEpisodesTagsUsersEpisodeTagss();
        $currentEpisodesTagsUsersEpisodeTagss = $this->getEpisodesTagsUsersEpisodeTagss();

        $combinationCollEpisodesTagsUsersEpisodeTagssScheduledForDeletion = $currentEpisodesTagsUsersEpisodeTagss->diff($episodesTagsUsersEpisodeTagss);

        foreach ($combinationCollEpisodesTagsUsersEpisodeTagssScheduledForDeletion as $toDelete) {
            call_user_func_array([$this, 'removeEpisodesTagsUsersEpisodeTags'], $toDelete);
        }

        foreach ($episodesTagsUsersEpisodeTagss as $episodesTagsUsersEpisodeTags) {
            if (!call_user_func_array([$currentEpisodesTagsUsersEpisodeTagss, 'contains'], $episodesTagsUsersEpisodeTags)) {
                call_user_func_array([$this, 'doAddEpisodesTagsUsersEpisodeTags'], $episodesTagsUsersEpisodeTags);
            }
        }

        $this->combinationCollEpisodesTagsUsersEpisodeTagssPartial = false;
        $this->combinationCollEpisodesTagsUsersEpisodeTagss = $episodesTagsUsersEpisodeTagss;

        return $this;
    }

    /**
     * Gets the number of ChildEpisodes, ChildUsers combination objects related by a many-to-many relationship
     * to the current object by way of the user_episode_tags cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildEpisodes, ChildUsers combination objects
     */
    public function countEpisodesTagsUsersEpisodeTagss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollEpisodesTagsUsersEpisodeTagssPartial && !$this->isNew();
        if (null === $this->combinationCollEpisodesTagsUsersEpisodeTagss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->combinationCollEpisodesTagsUsersEpisodeTagss) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getEpisodesTagsUsersEpisodeTagss());
                }

                $query = UserEpisodeTagsQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByEpisodeTag($this)
                    ->count($con);
            }
        } else {
            return count($this->combinationCollEpisodesTagsUsersEpisodeTagss);
        }
    }

    /**
     * Returns the not cached count of Episodes objects. This will hit always the databases.
     * If you have attached new Episodes object to this object you need to call `save` first to get
     * the correct return value. Use getEpisodesTagsUsersEpisodeTagss() to get the current internal state.
     *
     * @param Users $usersEpisodeTags
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return integer
     */
    public function countEpisodesTagss(Users $usersEpisodeTags = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createEpisodesTagssQuery($usersEpisodeTags, $criteria)->count($con);
    }

    /**
     * Associate a Episodes to this object
     * through the user_episode_tags cross reference table.
     *
     * @param Episodes $episodesTags,
     * @param Users $usersEpisodeTags
     * @return ChildTags The current object (for fluent API support)
     */
    public function addEpisodesTags(Episodes $episodesTags, Users $usersEpisodeTags)
    {
        if ($this->combinationCollEpisodesTagsUsersEpisodeTagss === null) {
            $this->initEpisodesTagsUsersEpisodeTagss();
        }

        if (!$this->getEpisodesTagsUsersEpisodeTagss()->contains($episodesTags, $usersEpisodeTags)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollEpisodesTagsUsersEpisodeTagss->push($episodesTags, $usersEpisodeTags);
            $this->doAddEpisodesTagsUsersEpisodeTags($episodesTags, $usersEpisodeTags);
        }

        return $this;
    }

    /**
     * Associate a Users to this object
     * through the user_episode_tags cross reference table.
     *
     * @param Users $usersEpisodeTags,
     * @param Episodes $episodesTags
     * @return ChildTags The current object (for fluent API support)
     */
    public function addUsersEpisodeTags(Users $usersEpisodeTags, Episodes $episodesTags)
    {
        if ($this->combinationCollEpisodesTagsUsersEpisodeTagss === null) {
            $this->initEpisodesTagsUsersEpisodeTagss();
        }

        if (!$this->getEpisodesTagsUsersEpisodeTagss()->contains($usersEpisodeTags, $episodesTags)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollEpisodesTagsUsersEpisodeTagss->push($usersEpisodeTags, $episodesTags);
            $this->doAddEpisodesTagsUsersEpisodeTags($usersEpisodeTags, $episodesTags);
        }

        return $this;
    }

    /**
     *
     * @param Episodes $episodesTags,
     * @param Users $usersEpisodeTags
     */
    protected function doAddEpisodesTagsUsersEpisodeTags(Episodes $episodesTags, Users $usersEpisodeTags)
    {
        $userEpisodeTags = new UserEpisodeTags();

        $userEpisodeTags->setEpisodesTags($episodesTags);
        $userEpisodeTags->setUsersEpisodeTags($usersEpisodeTags);

        $userEpisodeTags->setEpisodeTag($this);

        $this->addUserEpisodeTags($userEpisodeTags);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($episodesTags->isEpisodeTagUsersEpisodeTagssLoaded()) {
            $episodesTags->initEpisodeTagUsersEpisodeTagss();
            $episodesTags->getEpisodeTagUsersEpisodeTagss()->push($this, $usersEpisodeTags);
        } elseif (!$episodesTags->getEpisodeTagUsersEpisodeTagss()->contains($this, $usersEpisodeTags)) {
            $episodesTags->getEpisodeTagUsersEpisodeTagss()->push($this, $usersEpisodeTags);
        }

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($usersEpisodeTags->isEpisodesTagsEpisodeTagsLoaded()) {
            $usersEpisodeTags->initEpisodesTagsEpisodeTags();
            $usersEpisodeTags->getEpisodesTagsEpisodeTags()->push($episodesTags, $this);
        } elseif (!$usersEpisodeTags->getEpisodesTagsEpisodeTags()->contains($episodesTags, $this)) {
            $usersEpisodeTags->getEpisodesTagsEpisodeTags()->push($episodesTags, $this);
        }

    }

    /**
     * Remove episodesTags, usersEpisodeTags of this object
     * through the user_episode_tags cross reference table.
     *
     * @param Episodes $episodesTags,
     * @param Users $usersEpisodeTags
     * @return ChildTags The current object (for fluent API support)
     */
    public function removeEpisodesTagsUsersEpisodeTags(Episodes $episodesTags, Users $usersEpisodeTags)
    {
        if ($this->getEpisodesTagsUsersEpisodeTagss()->contains($episodesTags, $usersEpisodeTags)) {
            $userEpisodeTags = new UserEpisodeTags();
            $userEpisodeTags->setEpisodesTags($episodesTags);
            if ($episodesTags->isEpisodeTagUsersEpisodeTagssLoaded()) {
                //remove the back reference if available
                $episodesTags->getEpisodeTagUsersEpisodeTagss()->removeObject($this, $usersEpisodeTags);
            }

            $userEpisodeTags->setUsersEpisodeTags($usersEpisodeTags);
            if ($usersEpisodeTags->isEpisodesTagsEpisodeTagsLoaded()) {
                //remove the back reference if available
                $usersEpisodeTags->getEpisodesTagsEpisodeTags()->removeObject($episodesTags, $this);
            }

            $userEpisodeTags->setEpisodeTag($this);
            $this->removeUserEpisodeTags(clone $userEpisodeTags);
            $userEpisodeTags->clear();

            $this->combinationCollEpisodesTagsUsersEpisodeTagss->remove($this->combinationCollEpisodesTagsUsersEpisodeTagss->search($episodesTags, $usersEpisodeTags));

            if (null === $this->combinationCollEpisodesTagsUsersEpisodeTagssScheduledForDeletion) {
                $this->combinationCollEpisodesTagsUsersEpisodeTagssScheduledForDeletion = clone $this->combinationCollEpisodesTagsUsersEpisodeTagss;
                $this->combinationCollEpisodesTagsUsersEpisodeTagssScheduledForDeletion->clear();
            }

            $this->combinationCollEpisodesTagsUsersEpisodeTagssScheduledForDeletion->push($episodesTags, $usersEpisodeTags);
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
        $this->super_tag = null;
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
            if ($this->combinationCollPlaylistsTagsUsersPlaylistTagss) {
                foreach ($this->combinationCollPlaylistsTagsUsersPlaylistTagss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->combinationCollEpisodesTagsUsersEpisodeTagss) {
                foreach ($this->combinationCollEpisodesTagsUsersEpisodeTagss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collUserPlaylistTagss = null;
        $this->collUserEpisodeTagss = null;
        $this->combinationCollPlaylistsTagsUsersPlaylistTagss = null;
        $this->combinationCollEpisodesTagsUsersEpisodeTagss = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(TagsTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildTags The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[TagsTableMap::COL_UPDATED_AT] = true;

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
