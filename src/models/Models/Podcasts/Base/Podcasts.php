<?php

namespace Models\Podcasts\Base;

use \DateTime;
use \Exception;
use \PDO;
use Models\Episodes\Episodes;
use Models\Episodes\EpisodesQuery;
use Models\Episodes\Base\Episodes as BaseEpisodes;
use Models\Episodes\Map\EpisodesTableMap;
use Models\Podcasts\Podcasts as ChildPodcasts;
use Models\Podcasts\PodcastsQuery as ChildPodcastsQuery;
use Models\Podcasts\Map\PodcastsTableMap;
use Models\UserPodcasts\UserPodcasts;
use Models\UserPodcasts\UserPodcastsQuery;
use Models\UserPodcasts\Base\UserPodcasts as BaseUserPodcasts;
use Models\UserPodcasts\Map\UserPodcastsTableMap;
use Models\Users\Users;
use Models\Users\UsersQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'podcasts' table.
 *
 *
 *
 * @package    propel.generator.Models.Podcasts.Base
 */
abstract class Podcasts implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Podcasts\\Map\\PodcastsTableMap';


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
     * The value for the podcast_id field.
     *
     * @var        string
     */
    protected $podcast_id;

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
     * @var        ObjectCollection|UserPodcasts[] Collection to store aggregation of UserPodcasts objects.
     */
    protected $collUserPodcastss;
    protected $collUserPodcastssPartial;

    /**
     * @var        ObjectCollection|Episodes[] Collection to store aggregation of Episodes objects.
     */
    protected $collEpisodess;
    protected $collEpisodessPartial;

    /**
     * @var        ObjectCollection|Users[] Cross Collection to store aggregation of Users objects.
     */
    protected $collUserss;

    /**
     * @var bool
     */
    protected $collUserssPartial;

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
     * @var ObjectCollection|UserPodcasts[]
     */
    protected $userPodcastssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Episodes[]
     */
    protected $episodessScheduledForDeletion = null;

    /**
     * Initializes internal state of Models\Podcasts\Base\Podcasts object.
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
     * Compares this with another <code>Podcasts</code> instance.  If
     * <code>obj</code> is an instance of <code>Podcasts</code>, delegates to
     * <code>equals(Podcasts)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Podcasts The current object, for fluid interface
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
     * Get the [podcast_id] column value.
     *
     * @return string
     */
    public function getPodcastId()
    {
        return $this->podcast_id;
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
     * @return $this|\Models\Podcasts\Podcasts The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PodcastsTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [podcast_id] column.
     *
     * @param string $v new value
     * @return $this|\Models\Podcasts\Podcasts The current object (for fluent API support)
     */
    public function setPodcastId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->podcast_id !== $v) {
            $this->podcast_id = $v;
            $this->modifiedColumns[PodcastsTableMap::COL_PODCAST_ID] = true;
        }

        return $this;
    } // setPodcastId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Podcasts\Podcasts The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PodcastsTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\Podcasts\Podcasts The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PodcastsTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PodcastsTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PodcastsTableMap::translateFieldName('PodcastId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->podcast_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PodcastsTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PodcastsTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = PodcastsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Podcasts\\Podcasts'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(PodcastsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPodcastsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collUserPodcastss = null;

            $this->collEpisodess = null;

            $this->collUserss = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Podcasts::setDeleted()
     * @see Podcasts::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PodcastsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPodcastsQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PodcastsTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(PodcastsTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(PodcastsTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PodcastsTableMap::COL_UPDATED_AT)) {
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
                PodcastsTableMap::addInstanceToPool($this);
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

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\UserPodcasts\UserPodcastsQuery::create()
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

            if ($this->episodessScheduledForDeletion !== null) {
                if (!$this->episodessScheduledForDeletion->isEmpty()) {
                    \Models\Episodes\EpisodesQuery::create()
                        ->filterByPrimaryKeys($this->episodessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->episodessScheduledForDeletion = null;
                }
            }

            if ($this->collEpisodess !== null) {
                foreach ($this->collEpisodess as $referrerFK) {
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

        $this->modifiedColumns[PodcastsTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PodcastsTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PodcastsTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(PodcastsTableMap::COL_PODCAST_ID)) {
            $modifiedColumns[':p' . $index++]  = 'podcast_id';
        }
        if ($this->isColumnModified(PodcastsTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(PodcastsTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO podcasts (%s) VALUES (%s)',
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
                    case 'podcast_id':
                        $stmt->bindValue($identifier, $this->podcast_id, PDO::PARAM_STR);
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
        $pos = PodcastsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getPodcastId();
                break;
            case 2:
                return $this->getCreatedAt();
                break;
            case 3:
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

        if (isset($alreadyDumpedObjects['Podcasts'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Podcasts'][$this->hashCode()] = true;
        $keys = PodcastsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getPodcastId(),
            $keys[2] => $this->getCreatedAt(),
            $keys[3] => $this->getUpdatedAt(),
        );
        if ($result[$keys[2]] instanceof \DateTimeInterface) {
            $result[$keys[2]] = $result[$keys[2]]->format('c');
        }

        if ($result[$keys[3]] instanceof \DateTimeInterface) {
            $result[$keys[3]] = $result[$keys[3]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
            if (null !== $this->collEpisodess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'episodess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'episodess';
                        break;
                    default:
                        $key = 'Episodess';
                }

                $result[$key] = $this->collEpisodess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Models\Podcasts\Podcasts
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PodcastsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\Podcasts\Podcasts
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setPodcastId($value);
                break;
            case 2:
                $this->setCreatedAt($value);
                break;
            case 3:
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
        $keys = PodcastsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setPodcastId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCreatedAt($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUpdatedAt($arr[$keys[3]]);
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
     * @return $this|\Models\Podcasts\Podcasts The current object, for fluid interface
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
        $criteria = new Criteria(PodcastsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PodcastsTableMap::COL_ID)) {
            $criteria->add(PodcastsTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(PodcastsTableMap::COL_PODCAST_ID)) {
            $criteria->add(PodcastsTableMap::COL_PODCAST_ID, $this->podcast_id);
        }
        if ($this->isColumnModified(PodcastsTableMap::COL_CREATED_AT)) {
            $criteria->add(PodcastsTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(PodcastsTableMap::COL_UPDATED_AT)) {
            $criteria->add(PodcastsTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildPodcastsQuery::create();
        $criteria->add(PodcastsTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Models\Podcasts\Podcasts (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPodcastId($this->getPodcastId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUserPodcastss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserPodcasts($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEpisodess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEpisodes($relObj->copy($deepCopy));
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
     * @return \Models\Podcasts\Podcasts Clone of current object.
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
        if ('UserPodcasts' == $relationName) {
            $this->initUserPodcastss();
            return;
        }
        if ('Episodes' == $relationName) {
            $this->initEpisodess();
            return;
        }
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
     * If this ChildPodcasts is new, it will return
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
                    ->filterByPodcasts($this)
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
     * @return $this|ChildPodcasts The current object (for fluent API support)
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
            $userPodcastsRemoved->setPodcasts(null);
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
                ->filterByPodcasts($this)
                ->count($con);
        }

        return count($this->collUserPodcastss);
    }

    /**
     * Method called to associate a UserPodcasts object to this object
     * through the UserPodcasts foreign key attribute.
     *
     * @param  UserPodcasts $l UserPodcasts
     * @return $this|\Models\Podcasts\Podcasts The current object (for fluent API support)
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
        $userPodcasts->setPodcasts($this);
    }

    /**
     * @param  UserPodcasts $userPodcasts The UserPodcasts object to remove.
     * @return $this|ChildPodcasts The current object (for fluent API support)
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
            $userPodcasts->setPodcasts(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Podcasts is new, it will return
     * an empty collection; or if this Podcasts has previously
     * been saved, it will retrieve related UserPodcastss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Podcasts.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|UserPodcasts[] List of UserPodcasts objects
     */
    public function getUserPodcastssJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = UserPodcastsQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getUserPodcastss($query, $con);
    }

    /**
     * Clears out the collEpisodess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEpisodess()
     */
    public function clearEpisodess()
    {
        $this->collEpisodess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEpisodess collection loaded partially.
     */
    public function resetPartialEpisodess($v = true)
    {
        $this->collEpisodessPartial = $v;
    }

    /**
     * Initializes the collEpisodess collection.
     *
     * By default this just sets the collEpisodess collection to an empty array (like clearcollEpisodess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEpisodess($overrideExisting = true)
    {
        if (null !== $this->collEpisodess && !$overrideExisting) {
            return;
        }

        $collectionClassName = EpisodesTableMap::getTableMap()->getCollectionClassName();

        $this->collEpisodess = new $collectionClassName;
        $this->collEpisodess->setModel('\Models\Episodes\Episodes');
    }

    /**
     * Gets an array of Episodes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPodcasts is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Episodes[] List of Episodes objects
     * @throws PropelException
     */
    public function getEpisodess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEpisodessPartial && !$this->isNew();
        if (null === $this->collEpisodess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEpisodess) {
                // return empty collection
                $this->initEpisodess();
            } else {
                $collEpisodess = EpisodesQuery::create(null, $criteria)
                    ->filterByPodcasts($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEpisodessPartial && count($collEpisodess)) {
                        $this->initEpisodess(false);

                        foreach ($collEpisodess as $obj) {
                            if (false == $this->collEpisodess->contains($obj)) {
                                $this->collEpisodess->append($obj);
                            }
                        }

                        $this->collEpisodessPartial = true;
                    }

                    return $collEpisodess;
                }

                if ($partial && $this->collEpisodess) {
                    foreach ($this->collEpisodess as $obj) {
                        if ($obj->isNew()) {
                            $collEpisodess[] = $obj;
                        }
                    }
                }

                $this->collEpisodess = $collEpisodess;
                $this->collEpisodessPartial = false;
            }
        }

        return $this->collEpisodess;
    }

    /**
     * Sets a collection of Episodes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $episodess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPodcasts The current object (for fluent API support)
     */
    public function setEpisodess(Collection $episodess, ConnectionInterface $con = null)
    {
        /** @var Episodes[] $episodessToDelete */
        $episodessToDelete = $this->getEpisodess(new Criteria(), $con)->diff($episodess);


        $this->episodessScheduledForDeletion = $episodessToDelete;

        foreach ($episodessToDelete as $episodesRemoved) {
            $episodesRemoved->setPodcasts(null);
        }

        $this->collEpisodess = null;
        foreach ($episodess as $episodes) {
            $this->addEpisodes($episodes);
        }

        $this->collEpisodess = $episodess;
        $this->collEpisodessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseEpisodes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseEpisodes objects.
     * @throws PropelException
     */
    public function countEpisodess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEpisodessPartial && !$this->isNew();
        if (null === $this->collEpisodess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEpisodess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEpisodess());
            }

            $query = EpisodesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPodcasts($this)
                ->count($con);
        }

        return count($this->collEpisodess);
    }

    /**
     * Method called to associate a Episodes object to this object
     * through the Episodes foreign key attribute.
     *
     * @param  Episodes $l Episodes
     * @return $this|\Models\Podcasts\Podcasts The current object (for fluent API support)
     */
    public function addEpisodes(Episodes $l)
    {
        if ($this->collEpisodess === null) {
            $this->initEpisodess();
            $this->collEpisodessPartial = true;
        }

        if (!$this->collEpisodess->contains($l)) {
            $this->doAddEpisodes($l);

            if ($this->episodessScheduledForDeletion and $this->episodessScheduledForDeletion->contains($l)) {
                $this->episodessScheduledForDeletion->remove($this->episodessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Episodes $episodes The Episodes object to add.
     */
    protected function doAddEpisodes(Episodes $episodes)
    {
        $this->collEpisodess[]= $episodes;
        $episodes->setPodcasts($this);
    }

    /**
     * @param  Episodes $episodes The Episodes object to remove.
     * @return $this|ChildPodcasts The current object (for fluent API support)
     */
    public function removeEpisodes(Episodes $episodes)
    {
        if ($this->getEpisodess()->contains($episodes)) {
            $pos = $this->collEpisodess->search($episodes);
            $this->collEpisodess->remove($pos);
            if (null === $this->episodessScheduledForDeletion) {
                $this->episodessScheduledForDeletion = clone $this->collEpisodess;
                $this->episodessScheduledForDeletion->clear();
            }
            $this->episodessScheduledForDeletion[]= clone $episodes;
            $episodes->setPodcasts(null);
        }

        return $this;
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
        $collectionClassName = UserPodcastsTableMap::getTableMap()->getCollectionClassName();

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
     * to the current object by way of the user_podcasts cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPodcasts is new, it will return
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
                    ->filterByPodcasts($this);
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
     * to the current object by way of the user_podcasts cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $userss A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildPodcasts The current object (for fluent API support)
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
     * to the current object by way of the user_podcasts cross-reference table.
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
                    ->filterByPodcasts($this)
                    ->count($con);
            }
        } else {
            return count($this->collUserss);
        }
    }

    /**
     * Associate a Users to this object
     * through the user_podcasts cross reference table.
     *
     * @param Users $users
     * @return ChildPodcasts The current object (for fluent API support)
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
        $userPodcasts = new UserPodcasts();

        $userPodcasts->setUsers($users);

        $userPodcasts->setPodcasts($this);

        $this->addUserPodcasts($userPodcasts);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$users->isPodcastssLoaded()) {
            $users->initPodcastss();
            $users->getPodcastss()->push($this);
        } elseif (!$users->getPodcastss()->contains($this)) {
            $users->getPodcastss()->push($this);
        }

    }

    /**
     * Remove users of this object
     * through the user_podcasts cross reference table.
     *
     * @param Users $users
     * @return ChildPodcasts The current object (for fluent API support)
     */
    public function removeUsers(Users $users)
    {
        if ($this->getUserss()->contains($users)) {
            $userPodcasts = new UserPodcasts();
            $userPodcasts->setUsers($users);
            if ($users->isPodcastssLoaded()) {
                //remove the back reference if available
                $users->getPodcastss()->removeObject($this);
            }

            $userPodcasts->setPodcasts($this);
            $this->removeUserPodcasts(clone $userPodcasts);
            $userPodcasts->clear();

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
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->podcast_id = null;
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
            if ($this->collUserPodcastss) {
                foreach ($this->collUserPodcastss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEpisodess) {
                foreach ($this->collEpisodess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserss) {
                foreach ($this->collUserss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collUserPodcastss = null;
        $this->collEpisodess = null;
        $this->collUserss = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PodcastsTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildPodcasts The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[PodcastsTableMap::COL_UPDATED_AT] = true;

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
