<?php
namespace Application\TVDB;

use Application\TVDB\Series\Get as GetSeries;
use Application\TVDB\Series\GetZip as GetZip;

use Application\TVDB\DB\DbActors;
use Application\TVDB\DB\DbBanners;
use Application\TVDB\DB\DbControl;
use Application\TVDB\DB\DbEpisodes;
use Application\TVDB\DB\DbLanguages;
use Application\TVDB\DB\DbSeries;

use Library\Error;
use Library\Utilities\FormatVar;
use Library\MySql\Db;
use Library\MySql\Table;

/*
 *		Application\TVDB\Api is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Api
 *
 * TVDB API interface
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage TVDB
 */
class Api extends TVDBProperties
{
	/**
	 * updateTime
	 *
	 * GetUpdateTime class instance
	 * @var object $updateTime
	 */
	protected $updateTime;

	/**
	 * dbSeries
	 *
	 * DbSeries object instance
	 * @var $dbSeries
	 */
	protected $dbSeries;

	/**
	 * dbControl
	 *
	 * DbControl object instance
	 * @var object $dbControl
	 */
	protected $dbControl;

	/**
	 * control
	 *
	 * copy of values in the dbControl control record
	 * @var object $controlRecord
	 */
	protected $control;

	/**
	 * languageAbbreviation
	 *
	 * User's language abbreviation
	 * @var string $languageAbbreviation
	 */
	protected $languageAbbreviation;

	/**
	 * requestSeries
	 *
	 * Series list being requested
	 * @var string $requestSeries
	 */
	protected $requestSeries;

	/**
	 * lookupResults
	 *
	 * Results of the last processSeriesRequest call
	 * @var array $lookupResults = result array from last processSeriesRequest
	 */
	protected $lookupResults;

	/**
	 * Series
	 *
	 * Array containing all of the series matching seriesName
	 * @var object $series
	 */
	protected $series;

	/**
	 * seriesId
	 *
	 * Series Id for current series and queries
	 * @var integer $seriesId
	 */
	protected $seriesId;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param object|array $properties = current properties settings
	 * @param object|array $defaultProperties = default property/value settings
	 * @param array $cliMap = Cli parameter names to property name map array
	 * @param array $cliDbMap = Cli db names to property name map array
	 * @param string $series = (optional) series name
	 * @throws Application\TVDB\Exception, Library\DOM\Exception
	 */
	public function __construct($properties, $defaultProperties, $cliMap, $cliDbMap, $series=null)
	{
		parent::__construct($properties, $defaultProperties, $cliMap, $cliDbMap);

		$this->lookupResults = null;
		$this->dbControl = null;
		$this->control = null;
		$this->updateTime = null;

		$this->requestSeries = '';
		$this->languageAbbreviation = 'en';

		$dsn = sprintf('%s:host=%s;port=%s;charset=UTF8;dbname=%s', $this->DbDriver,
																    $this->DbHost,
																	$this->DbPort,
																	$this->DbName);

		$attributes = array();

		$this->table = 'Control';

		$this->db = new Db($dsn, $this->DbUser, $this->DbPassword, $attributes);
		$this->dbLink = $this->db->dbLink();

		$this->randomSeed();

		$updateTime = new GetUpdateTime(sprintf('%sUpdates.php?type=none', $this->AccountUrl));
		$this->updateTime = $updateTime->timestamp();
		unset($updateTime);

		$this->ApiUrl = sprintf("%s%s/", $this->AccountUrl, $this->AccountKey);

		$tableLink = new Table($this->dbLink, $this->DbUser, $this->DbPassword, $attributes);
		if (! $tableLink->tableExists('Control'))
		{
			$userLanguage = new UserLanguage($this->AccountNumber, $this->AccountUrl);
			$languageAbbreviation = $userLanguage->abbreviation;
			unset($userLanguage);

			$this->table = 'Control';
			$this->control = new Control();
			$this->dbControl = new DbControl($this->dbLink, $this->AccountOwner, $this->AccountPassword, array('DBO_OPTION_CREATE_TABLE'));

			$this->control->ApiUrl = $this->AccountUrl;
			$this->control->ApiKey = $this->AccountKey;

			$this->control->AccountNumber = $this->AccountNumber;
			$this->control->AccountOwner = $this->AccountOwner;
			$this->control->AccountPassword = $this->AccountPassword;
			$this->control->UserLanguage = $languageAbbreviation;

			$this->control->TVDBLastUpdateTime = $this->updateTime;
			$this->control->LastUpdated = $this->updateTime;
			$this->control->LastRun = date('Y-m-d H:i:s');

			$this->dbControl->insertData(null, $this->control);
			unset($this->dbControl, $this->control);

			$this->table = 'Languages';
			$languages = new Languages($this->ApiUrl . "languages.xml");
			$dbLanguages = new DbLanguages($this->dbLink, $this->AccountOwner, $this->AccountPassword, array('DBO_OPTION_CREATE_TABLE'));
			$dbLanguages->insertData(null, $languages);
			unset($dbLanguages, $languages);

			$this->createEmptyTable('Actors');
			$this->createEmptyTable('Banners');
			$this->createEmptyTable('Episodes');
			$this->createEmptyTable('Series');
		}

		$this->table = 'Control';
		$this->dbControl = new DbControl($this->dbLink, $this->AccountOwner, $this->AccountPassword);
		$this->dbControl->queryResult = $this->dbControl->query($this->dbControl->createSqlSelect());

		$dataObject = $this->dbControl->fetchObject('Application\TVDB\DB\DbObject');
		$this->control = new Control($dataObject->properties());

		$this->control->LastRun = date('Y-m-d H:i:s');
		$this->dbControl->updateData(null, $this->control);

		if ($series !== null)
		{
			$this->processLookupRequests($series);
		}
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
	}

	/**
	 * __toString
	 *
	 * Returns a printable list of properties and values
	 * @return string $buffer = list
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(true);

		$buffer = sprintf("%s:\n", get_class());

		$vars = get_object_vars($this);
		foreach($vars as $name => $value)
		{
			$buffer .= FormatVar::format($value, $name);
		}

		return $buffer;
	}

	/**
	 * processLookupRequests
	 *
	 * Process a series of series lookup requests
	 * @param string|array $series = (optional) string or array containing series names to lookup
	 * @param integer $maxSeries = (optional) maximum number of series entries to return, 0 = all
	 * @returns array $results = array containing results of each search
	 * @throws Exception
	 */
	public function processLookupRequests($series=null, $maxSeries=0)
	{
		if (($series = $this->requestSeries($series)) == null)
		{
			throw new Exception(Error::code('StringOrListExpected'));
		}

		if (! is_array($series))
		{
			$series = explode(',', $series);
		}

		$this->lookupResults = array();
		foreach($series as $index => $seriesName)
		{
			$this->lookupResults[$seriesName] = $this->lookupSeries($seriesName, $maxSeries);
		}

		return $this->lookupResults;
	}

	/**
	 * lookupSeries
	 *
	 * Lookup the provided series names and return an array of records with at most $maxSeries entries
	 * @param string $seriesName = the series name to search for
	 * @param integer $maxSeries = (optional) maximum number of series entries to return, 0 = all
	 * @return array $series = array of Records\Series objects describing the series' found
	 */
	public function lookupSeries($seriesName, $maxSeries=0)
	{
		$tvdbSeries = new Series('http://thetvdb.com/api/GetSeries.php?seriesname=' . $seriesName);

		$result = $tvdbSeries->records();
		unset($tvdbSeries);

		if (($maxSeries != 0) && ($maxSeries < count($result)))
		{
			$result = array_slice($result, 0, $maxSeries);
		}

		return $result;
	}

	/**
	 * insertSeriesEpisodes
	 *
	 * Insert the series and episode records in tvdbFullSeries
	 */
	public function insertSeriesEpisodes()
	{
		$tvdbRecords = $this->tvdbFullSeries->records();
		$this->tvdbFullSeries->records($this->tvdbFullSeries->seriesRecord());

		$this->insertSeries($this->tvdbFullSeries);

		$this->tvdbFullSeries->records($this->tvdbFullSeries->episodeRecords());
		$this->insertEpisodes($this->tvdbFullSeries);

		$this->tvdbFullSeries->records($tvdbRecords);
	}

	/**
	 * insertSeries
	 *
	 * Insert the current series data from provided list
	 * @param object $data = Series class object
	 * @throws Exception
	 */
	public function insertSeries($data)
	{
		$this->addSeriesRecords('Series', $data);
	}

	/**
	 * insertEpisodes
	 *
	 * Insert the current episode data from tvdbFullSeries property
	 * @param object $data = Episodes class object
	 * @throws Exception
	 */
	public function insertEpisodes($data)
	{
		$this->addSeriesRecords('Episodes', $data);
	}

	/**
	 * insertBanners
	 *
	 * Insert the current banner data from parameter data link
	 * @param string $data = Banners object containing data records to insert
	 * @throws Exception
	 */
	public function insertBanners($data)
	{
		$this->addSeriesRecords('Banners', $data);
	}

	/**
	 * insertActors
	 *
	 * Insert the current actors data from parameter data link
	 * @param string $data = Actors object containing data records to insert
	 * @throws Exception
	 */
	public function insertActors($data)
	{
		$this->addSeriesRecords('Actors', $data);
	}

	/**
	 * addSeriesRecords
	 *
	 * Insert (replace)/update series table data
	 * @param string $table = (case sensitive) database table name
	 * @param object $data = tvdb data class object
	 * @throws Exception
	 */
	public function addSeriesRecords($table, $data)
	{
		$handle = $this->getTableHandle($table);

		if ($this->table === 'Series')
		{
			$seriesId = null;
		}
		else
		{
			$seriesId = $this->seriesId;
		}

		if ($handle->deleteRecords($this->seriesId) === false)
		{
			throw new Exception(Error::code('DbDeleteRecordError'));
		}

		$handle->insertData($seriesId, $data);
		$handle = null;
	}

	/**
	 * getTableHandle
	 *
	 * Get a handle to the requested table
	 * @param string $table = (case sensitive) database table name
	 * @return object $handle = table handle
	 * @throws Exception
	 */
	public function getTableHandle($table)
	{
		$this->table = $table;
		$dbTable = sprintf('Application\TVDB\DB\Db%s', $this->table);

		$handle = new $dbTable($this->dbLink, $this->AccountOwner, $this->AccountPassword);
		return $handle;
	}

	/**
	 * getSeriesEpisodes
	 *
	 * Get Series and Episode(s) information
	 * @param integer $seriesId = seriesid of the series to load
	 * @return object $tvdbFullSeries = GetSeries object containing all available information for requested series
	 * @throws Exception
	 */
	public function getSeriesEpisodes($seriesId)
	{
		$seriesId = $this->seriesId($seriesId);
		return $this->getSeriesEpisodesByUrl($this->createSeriesUrl($seriesId, '.xml'));
	}

	/**
	 * getSeriesEpisodesByUrl
	 *
	 * Get Series and Episode(s) by URL
	 * @param string $url = url of the series to load
	 * @return object $tvdbFullSeries = GetSeries object containing all available information for requested series
	 * @throws Exception
	 */
	public function getSeriesEpisodesByUrl($url)
	{
		if ($this->tvdbFullSeries)
		{
			unset($this->tvdbFullSeries);
		}

		$this->tvdbFullSeries = new GetSeries($url);

		return $this->tvdbFullSeries;
	}

	/**
	 * createSeriesUrl
	 *
	 * Create a url to the requested seriesId parameter
	 * @param integer|string $seriesId = series to get a url for
	 * @param string $extension = (optional) file extension (default = .xml)
	 * @return string $url = created series url
	 */
	public function createSeriesUrl($seriesId, $extension='.xml')
	{
		return sprintf("%sseries/%s/all/%s%s", $this->ApiUrl, $seriesId, $this->languageAbbreviation, $extension);
	}

	/**
	 * lookupResults
	 *
	 * Get/set the lookupResults property
	 * @param array $lookupResults = (optional) array to store in property, null to query only
	 * @return array $lookupResults = current array settings.
	 */
	public function lookupResults($lookupResults=null)
	{
		if ($lookupResults !== null)
		{
			$this->lookupResults = $lookupResults;
		}

		return $this->lookupResults;
	}

	/**
	 * requestSeries
	 *
	 * Get/set the requestSeries property
	 * @param array $requestSeries = (optional) array to store in property, null to query only
	 * @return array $requestSeries = current array settings.
	 */
	public function requestSeries($requestSeries=null)
	{
		if ($requestSeries !== null)
		{
			$this->requestSeries = $requestSeries;
		}

		return $this->requestSeries;
	}

	/**
	 * seriesInDb
	 *
	 * Returns SeriesId if the series is in the local TVDB, throws exception if not
	 * @param string $seriesName = name of series
	 * @return integer $id = series id if found, null if not
	 * @throws Exception
	 */
	public function seriesInDb($seriesName)
	{
		$handle = $this->getTableHandle('Series');
		$this->dbSeries = null;

		$sql = $handle->createSqlSelect(sprintf("`SeriesName` = '%s'", $seriesName));

		try
		{
			$handle->queryResult = $handle->query($sql);

			if (! $this->dbObject = $handle->fetchObject('\Application\TVDB\DB\DbObject'))
			{
				return null;
			}
		}
		catch(MySqlException $exception)
		{
			throw new Exception(Error::code('DbQueryError'));
		}
		catch(mysqli_sql_exception $exception)
		{
			throw new Exception(Error::code('DbSqlError'));
		}

		return $this->dbObject->id;
	}

	/**
	 * seriesId
	 *
	 * Get/set the seriesId property
	 * @param string|integer $seriesId = (optional) series identified (tvdb)
	 * @return string $seriesId
	 */
	public function seriesId($seriesId=null)
	{
		if ($seriesId !== null)
		{
			$this->seriesId = $seriesId;
		}

		return $this->seriesId;
	}

	/**
	 * tvdbFullSeries
	 *
	 * Get the tvdbFullSeries property
	 * @return object $tvdbFullSeries = TVDB\DB\Get\Series instance.
	 */
	public function tvdbFullSeries()
	{
		return $this->tvdbFullSeries;
	}

	/**
	 * createEmptyTable
	 *
	 * Create an empty table named $table
	 * @param string $table = the name of the table to create
	 */
	protected function createEmptyTable($table)
	{
		$this->table = $table;
		$class = sprintf('Application\TVDB\DB\Db%s', $table);

		$dbTable = new $class($this->dbLink, $this->AccountOwner, $this->AccountPassword, array('DBO_OPTION_CREATE_TABLE'));
		unset($dbTable);

	}

	/**
	 * getUserLanguage
	 *
	 * Get the current account user language from the remote TVDB database
	 * @return string $abbreviation = 2 character user language abbreviation
	 */
	public function getUserLanguage()
	{
		$userLanguage = new UserLanguage($this->AccountNumber, $this->AccountUrl);
		return $userLanguage->abbreviation;
	}

	/**
	 * setLanguage
	 *
	 * Set the user's language
	 * @param string $language = (optional) abbreviation of the language to use, default = 'en'
	 * @return string $originalLanguage = original value of the languageAbbreviation class property
	 * @throws Exception
	 */
	public function setLanguage($language='en')
	{
		try
		{
			$this->languageInDb($language);
		}
		catch(Exception $exception)
		{
			if ($exception->code === Error::code('TVDB_InvalidLanguage'))
			{
				return $this->languageAbbreviation;
			}

			throw new Exception($exception->getMessage(), $exception->getCode());
		}

		$originalLanguage = $this->languageAbbreviation;
		$this->languageAbbreviation = $language;
		return $originalLanguage;
	}

	/**
	 * languageInDb
	 *
	 * Throws exception if the language abbreviation is not valid
	 * @param string $abbreviation = language abbreviation
	 * @throws Exception
	 */
	public function languageInDb($abbreviation)
	{
		$handle = $this->getTableHandle('Languages');
		$this->dbLanguages = null;

		$sql = $handle->createSqlSelect(sprintf("`abbreviation` = '%s'", $abbreviation));

		try
		{
			$handle->queryResult = $handle->query($sql);

			if (! $this->dbObject = $handle->fetchObject('\Application\TVDB\DB\DbObject'))
			{
				return null;
			}
		}
		catch(MySqlException $exception)
		{
			throw new Exception(Error::code('DbQueryError'));
		}
		catch(mysqli_sql_exception $exception)
		{
			throw new Exception(Error::code('DbSqlError'));
		}

		if ($this->dbObject->abbreviation !== $abbreviation)
		{
			throw new Exception(Error::code('TVDB_InvalidLanguage'));
		}

	}

	/**
	 * randomSeed
	 *
	 * Make random seed and initialize random number generator
	 */
	private function randomSeed()
	{
  		list($usec, $sec) = explode(' ', microtime());
  		srand((float) $sec + ((float) $usec * 100000));
	}

}
