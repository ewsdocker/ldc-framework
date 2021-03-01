<?php
namespace ApplicationTests\TVDB;

/*
 * 		ApplicationTests\TVDB\ApplicationZipApiTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * TVDB\ApplicationZipApiTest.
 *
 * TVDB ZipApi class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage ApplicationApiTest
 */

class ApplicationZipApiTest extends TVDBTestLib
{
	/**
	 * __construct
	 *
	 * Class constructor - pass parameters on to parent class
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assertSetup(1, 0, 0, array('Application\Launcher\Testing\Base', 'assertCallback'));
	}

	/**
	 * __destruct
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * assertionTests
	 *
	 * run the current assertion test steps
	 * @parm string $logger = (optional) name of the logger to use, null for none
	 */
	public function assertionTests($logger=null, $format=null)
	{
		$this->a_table = 'Control';
		$this->a_xmlFile = 'control.xml';

		parent::assertionTests($logger, $format);

		$this->labelBlock('ApplicationZipApiTest.', 60, '=');

		$seriesName = $this->a_seriesName;
		$this->a_seriesName = null;

		$this->a_newTableDriver($this->a_dsnParam, $this->a_userParam, $this->a_passwordParam);

		if ($this->a_dbDeleteAll)
		{
			$tableNames = array('Actors', 'Banners', 'Control', 'Episodes', 'Languages', 'Series');
			$this->a_dropTables($tableNames);
		}

		$this->a_defaults();

		$seriesId = $this->a_seriesid;
		$this->a_seriesid = null;

		if ($this->a_updateSeries=1)
		{
			$this->a_newApi();
			$this->a_seriesInDb($seriesName);

			if ($this->a_seriesid === null)
			{
				$this->assertLogMessage("Series $seriesName was not found in the local TVDB database.");
				$this->a_lookupSeries($seriesName, 1);

				if (! $this->a_seriesRecords)
				{
					$this->assertLogMessage("Unable to get seriesId for $seriesName");
				}
				else
				{
					$returnedName = $this->a_seriesRecords[0]->SeriesName;
					$this->a_compareSeriesRecord($seriesName);

					if (! $this->a_data)
					{
						$this->a_outputAndDie("Series name mismatch: $seriesName /= $returnedName");
					}

					$this->a_seriesid = $this->a_seriesRecords[0]->id;

					$this->a_getSeriesZip($this->a_seriesid);
					$this->a_fullSeriesEpisodes();

					$this->a_insertSeriesEpisodes();
					$this->a_insertSeriesBanners();
					$this->a_insertSeriesActors();
				}
			}
			else
			{
				$this->assertLogMessage("Series $seriesName has seriesId = " . $this->a_seriesid);
			}
		}
		else
		{
			$this->a_newApi();

			$ids = explode(',', $seriesId);

			foreach($ids as $index => $series)
			{
				$this->a_seriesid = $series;
				$this->a_getSeriesZip($this->a_seriesid);

				$this->a_fullSeriesEpisodes();

				$this->a_insertSeriesEpisodes();
				$this->a_insertSeriesBanners();
				$this->a_insertSeriesActors();
			}
		}

		$this->a_deleteApi();
	}

	/**
	 * a_compareSeriesRecord
	 *
	 * Compare the seriesName in the returned record with the requested seriesName for an EXACT match
	 * @param string $seriesName = expected seriesName
	 */
	public function a_compareSeriesRecord($seriesName)
	{
		$this->labelBlock('Compare Series Record.', 40, '*');

		$this->a_seriesName = $seriesName;
		$this->a_showData($this->a_seriesName, 'Series Name');

		$assertion = '($this->a_data = ($this->a_seriesName === $this->a_seriesRecords[0]->SeriesName));';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'Comparison Result');
	}

	/**
	 * a_lookupSeries
	 *
	 * Lookup the seriesId from master TVDB database
	 * @param  $seriesName
	 */
	public function a_lookupSeries($seriesName)
	{
		$this->labelBlock('Lookup Series In REMOTE TVDB.', 40, '*');

		$this->a_seriesName = $seriesName;
		$this->a_showData($this->a_seriesName, 'Series Name');

//$this->a_seriesRecords = $this->a_api->lookupSeries($this->a_seriesName);

		$assertion = '(($this->a_seriesRecords = $this->a_api->lookupSeries($this->a_seriesName)) !== false);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

//    	$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_seriesRecords, 'SeriesRecords');
	}

	/**
	 * a_seriesInDb
	 *
	 * Get seriesId from the TVDB local database
	 * @param  $seriesName
	 */
	public function a_seriesInDb($seriesName)
	{
		$this->labelBlock('Series In Db.', 40, '*');

		$this->a_seriesName = $seriesName;
		$this->a_showData($this->a_seriesName, 'Series Name');

		$assertion = '(($this->a_seriesid = $this->a_api->seriesInDb($this->a_seriesName)) !== false);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_seriesid, 'SeriesId');
	}

	/**
	 * a_getSeriesZip
	 *
	 * Get series zip file
	 * @param  $seriesNumber
	 */
	public function a_getSeriesZip($seriesNumber, $zipUrl=null, $extractUrl=null)
	{
		$this->labelBlock('Get Series Zip.', 40, '*');

		$this->a_seriesNumber = $seriesNumber;
		$this->a_zipUrl = $zipUrl;
		$this->a_extractUrl = $extractUrl;

		$this->a_showData($this->a_seriesNumber, 'Series Number');
		$this->a_showData($this->a_zipUrl, 'Zip Url');
		$this->a_showData($this->a_extractUrl, 'Extract Url');

		$assertion = '$this->a_zip = $this->a_api->getSeriesZip($this->a_seriesNumber, $this->a_zipUrl, $this->a_extractUrl);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_zip, 'Series Episodes Records');
	}

	/**
	 * a_processSeries
	 *
	 * Process the series number
	 * @param integer|string $seriesNumber =  series number to process
	 */
	public function a_processSeries($seriesNumber)
	{
    	$this->labelBlock('Process Series.', 40, '*');

    	$this->a_showData($seriesNumber, 'Series Number');

    	$this->a_getSeriesEpisodes($seriesNumber);

		if ($this->a_seriesEpisodes)
		{
			$this->a_getSeriesResults();
		}

	}

	/**
	 * a_createSeries
	 *
	 * Create the series tables
	 * @param integer|string $seriesNumber =  series number to process
	 */
	public function a_createSeries($seriesId, $dataLink)
	{
    	$this->labelBlock('Create Series.', 40, '*');

    	$this->a_seriesId = $seriesId;
    	$this->a_dataLink = $dataLink;

    	$this->a_showData($this->a_seriesId, 'SeriesId');
    	$this->a_showData($this->a_dataLink, 'DataLink');

    	$assertion = '(($this->a_records = $this->a_seriesEpisodes->records()) !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_records, 'Series Episodes Records');
    }

	/**
     * a_getSeriesResults
     *
     * Get all series/episodes/banners/actors info
     */
    public function a_getSeriesResults()
    {
    	$this->labelBlock('Get Series Results.', 40, '*');

    	$assertion = '(($this->a_records = $this->a_seriesEpisodes->records()) !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_records, 'Series Episodes Records');
    }

    /**
     * a_insertSeriesBanners
     *
     * Insert the current banners data into the database tables
     */
    public function a_insertSeriesBanners()
    {
    	$this->labelBlock('Insert Series Banners.', 40, '*');

//$this->a_result = $this->a_api->insertZipBanners();

		$assertion = '(($this->a_result = $this->a_api->insertZipBanners()) === null);';
    	if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
    	{
			$this->a_outputAndDie();
    	}

    	$this->a_exceptionCaughtFalse();

    	$this->a_showData($this->a_result, 'Result');
	}

    /**
     * a_insertSeriesActors
     *
     * Insert the current actor data into the database tables
     */
    public function a_insertSeriesActors()
    {
    	$this->labelBlock('Insert Series Actors.', 40, '*');

		$assertion = '(($this->a_result = $this->a_api->insertZipActors()) === null);';
    	if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
    	{
			$this->a_outputAndDie();
    	}

    	$this->a_exceptionCaughtFalse();

    	$this->a_showData($this->a_result, 'Result');
	}

	/**
     * a_insertSeriesEpisodes
     *
     * Insert the current fullSeriesEpisodes data into the database tables
     */
    public function a_insertSeriesEpisodes()
    {
    	$this->labelBlock('Insert Series Episodes.', 40, '*');

		$assertion = '(($this->a_result = $this->a_api->insertSeriesEpisodes()) === null);';
    	if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
    	{
			$this->a_outputAndDie();
    	}

    	$this->a_exceptionCaughtFalse();

    	$this->a_showData($this->a_result, 'Result');
	}

    /**
     * a_fullSeriesEpisodes
     *
     * Get the current fullSeriesEpisodes class instance
     */
    public function a_fullSeriesEpisodes()
    {
    	$this->labelBlock('Full Series Episodes.', 40, '*');

    	$assertion = '(($this->a_fullSeriesEpisodes = $this->a_api->tvdbFullSeries()) !== true);';
    	if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
    	{
			$this->a_outputAndDie();
    	}

    	$this->a_exceptionCaughtFalse();

    	$this->a_showData($this->a_fullSeriesEpisodes, 'Full Series Episodes');
	}

    /**
     * a_getSeriesEpisodes
     *
     * Get all series/episodes/banners/actors info
     * @param string $series = series number
     */
    public function a_getSeriesEpisodes($series)
    {
    	$this->labelBlock('Get Series Episodes.', 40, '*');

    	$this->a_series = $series;
    	$this->a_showData($this->a_series, 'Series');

    	$assertion = '(($this->a_seriesEpisodes = $this->a_api->getSeriesEpisodes($this->a_series)) !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_seriesEpisodes, 'Series Episodes');
    }

	/**
     * a_requestSeries
     *
     * Set/Get the seriesRequest property
     * @param string $seriesRequest = (optional) seriesRequest property settings, null to query only
     */
    public function a_requestSeries($seriesRequest=null)
    {
    	$this->labelBlock('Lookup Results.', 40, '*');

    	$this->a_seriesRequest = $seriesRequest;
    	$this->a_showData($this->a_seriesRequest, 'Series Request');

    	$assertion = '(($this->a_request = $this->a_api->requestSeries($this->a_seriesRequest)) !== null);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_request, 'Request');
    }

    /**
     * a_processLookupRequests
     *
     * Lookup series using processLookupRequests
     * @param string $seriesList = (optional) seriesList property settings, null to use current list
     */
    public function a_processLookupRequests($seriesList=null)
    {
    	$this->labelBlock('Process Lookup Requests.', 40, '*');

    	$this->a_seriesList = $seriesList;
    	$this->a_showData($this->a_seriesList, 'Series List');

    	$assertion = '(($this->a_results = $this->a_api->processLookupRequests($this->a_seriesList)) !== null);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_results, 'Results');
    }

	/**
     * a_deleteApi
     *
     * Delete the current Node class instance
     */
    public function a_deleteApi()
    {
    	$this->labelBlock('Delete Api.', 40, '*');

    	$assertion = '(($this->a_api = null) === null);';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

	/**
     * a_lookupResults
     *
     * Get the results from the last processSeriesRequests (or new TVDBApi)
     * @param array $setResults = (optional) setResults property settings, null to query only
     */
    public function a_lookupResults($setResults=null)
    {
    	$this->labelBlock('Lookup Results.', 40, '*');

    	$this->a_setResults = $setResults;
    	$this->a_showData($this->a_setResults, 'SetResults');

    	$assertion = '(($this->a_results = $this->a_api->lookupResults($this->a_setResults)) !== true);';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_results, 'Results');
    }

    /**
	 * a_defaults
	 *
	 * Setup default property settings in $a_defaultProperties
	 */
	public function a_defaults()
	{
		$this->labelBlock('Setup Defaults.', 40, '*');

		include('/usr/local/share/php/PHPProjectLibrary/Application/Utility/ApplicationIncludes.php');

		$this->a_defaultProperties = array(

			/**
			 * Application_Adapter
			 *
			 * The configuration file type adapter ('ini', 'xml', 'db')
			 * @var string Config_Adapter
			 */
			'Application_Adapter' => 'xml',

			/**
			 * Application_Folder
			 *
			 * The folder in the root path that contains the configuration file,
			 *   or the absolute path (must begin with '/') to the configuration folder
			 * @var string Config_Folder
			 */
			'Application_Folder' => 'Application/TVDB',

			/**
			 * Application_ConfigName
			 *
			 * The configuration file name (only - no file type)
			 * @var string Application_FileName = the configuration file name.
			 */
			'Application_ConfigName' => 'TVDBControl',

			/**
			 * Application_IoAdapter
			 *
			 * The configuration I/O driver name ('file', 'stream')
			 * @var string Application_IoAdapter
			 */
			'Application_IoAdapter' => 'file',

			/**
			 * Application_IoAdapterType
			 *
			 * The type of I/O adapter
			 * @var string Application_IoAdapterType
			 */
			'Application_IoAdapterType' => 'fileobject',

			/**
			 * Application_ConfigSection
			 *
			 * Configuration group name (default = TVDB)
			 * @var string Application_ConfigSection
			 */
			'Application_ConfigSection' => 'TVDB',

			/**
			 * DbDriver
			 *
			 * Database Driver name
			 * @var string Application_DbDriver
			 */
			'DbDriver'	  => 'mysql',

			/**
			 * DbHost
			 *
			 * Database host name/address
			 * @var string Application_DbHost
			 */
			'DbHost' => '22.33.22.1',

			/**
			 * DbPort
			 *
			 * Database host port number
			 * @var string Application_DbPort
			 */
			'DbPort' => '3306',

			/**
			 * DbName
			 *
			 * Database name
			 * @var string Application_DbName
			 */
			'DbName' => 'TVDB',

			/**
			 * DbUser
			 *
			 * Database User name
			 * @var string Application_DbUser
			 */
			'DbUser' => 'phplibuser',

			/**
			 * DbPassword
			 *
			 * Database user password
			 * @var string Application_DbPwd
			 */
			'DbPassword' => 'phplibpwd',

			/**
			 * Execute_Type
			 *
			 * The type of execution for this module
			 * 		can be 'testing', 'development' or 'production' (default)(NOTE: Character case is important!!)
			 * @var string Execute_Type
			 */
			'Execute_Type' => 'testing',

		);

		$this->a_cliApplicationMap = array(
								'applicationadapter'	=> 'Application_Adapter',
								'applicationfolder'		=> 'Application_Folder',
								'applicationio'			=> 'Application_IoAdapter',
								'applicationiotype'		=> 'Application_IoAdapterType',
								'applicationconfig'		=> 'Application_ConfigName',
								'applicationsection'	=> 'Application_ConfigSection',

								'host'					=> 'DbHost',
								'port'					=> 'DbPort',
								'dbdriver'				=> 'DbDriver',
								'db'					=> 'DbName',
								'dbuser'				=> 'DbUser',
								'dbpwd'					=> 'DbPassword',
								);

		$this->a_cliDbMap = array(
								'DbHost'				=> 'host',
								'DbPort'				=> 'port',
								'DbDriver'				=> 'dbdriver',
								'DbName'				=> 'db',
								'DbUser'				=> 'dbuser',
								'DbPassword'			=> 'dbpwd',
								);

		$this->a_cliMap = array_merge($optionMap, $this->a_cliApplicationMap);
		$this->a_defaultProperties= array_merge($defaultProperties, $this->a_defaultProperties);

		$this->a_showData($this->a_defaultProperties, 'Default Application Properties');
		$this->a_showData($this->a_cliMap, 'Cli Map');
		$this->a_showData($this->a_cliDbMap, 'Cli Db Map');
	}

	/**
	 * a_newApi
	 *
	 * create a new Api object
	 * @param string $expected = expected class name
	 */
	public function a_newApi($expected='\Application\TVDB\ZipApi')
	{
		$this->labelBlock('New Api.', 40, '*');

		$this->a_expected = $expected;

		$this->a_showData($this->a_expected, 'Expected');

		$assertion = '$this->a_api = new \Application\TVDB\ZipApi($this->properties, $this->a_defaultProperties, $this->a_cliMap, $this->a_cliDbMap, $this->a_seriesName);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, $expected, get_class($this->a_api));

		$this->a_showData((string)$this->a_api, 'Api');
	}

}
