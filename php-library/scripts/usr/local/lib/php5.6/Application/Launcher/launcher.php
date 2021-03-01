<?php
namespace Application;
use Application\Launcher\Main;

/*
 *		launcher is copyright � 2015, 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * launcher
 *
 * Application launcher
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage Launcher
 */

	/**
	 * Execute with the following flags:
	 *
	 *  configadapter = configuration file type adapter ('xml', 'ini', 'db')
	 *  configfolder = installation-root-based folder or absolute path to the configuration file folder (default = 'Library')
	 *  configio = configuration i/o adapter ('file', 'stream', ...) (default = 'file')
	 *  configiotype = configuration i/o adapter type ('fileobject', ...) (default = 'fileobject')
	 *  configname = configuration file name (no type) (default = 'launcherConfig')
	 *
	 *  execute = type of execution: production (default), development, testing
	 *
	 *  exclude = list of process numbers to be excluded
	 *  include = list of process numbers to be included, or 'all' to run all processes
	 *
	 *  logformat = log output format: none, log, timestamp (default), xml
	 *  logsub = name of the log subcategory to use, default = Launcher
	 *
	 *  tests = name of the file containing the tests to run (eg: tests=standardTests)
	 *  type = type of the tests to run: 'Application' or 'Library' (default = 'Library')
	 *
	 * 	verbose = how much output to generate: 0 = none, 1 = minimal, 2 = lots of output
	 *
	 */

	/**
	 * Example:
	 *
	 *   Using defaults for everything except for the tests to run (tests=standardTests):
	 *
	 *       phpTests include=1-10 exclude=4,5
	 *
	 *   Using defaults for everything except for the test scripts and the tests to run
	 *
	 *   	 phpTests tests=genericTests include=1
	 *
	 */

	/**
	 * defaultProperties
	 *
	 * An array containing default values for user settable parameters to pass to the launcher program
	 * @var array $defaultProperties
	 */
	$defaultProperties = array(

		/**
		 * Install_Root
		 *
		 * TEMPORARY installation root - until the Autoloader is loaded and active
		 * @var string $Install_Root
		 */
		'Install_Root'		=> '/usr/local/include/php/PHPProjectLibrary/',

		/**
		 * Config_Adapter
		 *
		 * The configuration file type adapter ('ini', 'xml', 'db')
		 * @var string Config_Adapter
		 */
		'Config_Adapter' => 'xml',

		/**
		 * Config_Folder
		 *
		 * The folder in the root path that contains the configuration file,
		 *   or the absolute path (must begin with '/') to the configuration folder
		 * @var string Config_Folder
		 */
		'Config_Folder' => 'Application/Launcher',

		/**
		 * Config_FileName
		 *
		 * The configuration file name (only - no file type)
		 * @var string Config_FileName = the configuration file name.
		 */
		'Config_FileName' => 'launcherConfig',

		/**
		 * Config_IoAdapter
		 *
		 * The configuration I/O driver name ('file', 'stream')
		 * @var string Config_IoAdapter
		 */
		'Config_IoAdapter' => 'file',

		/**
		 * Config_IoAdapterType
		 *
		 * The type of I/O adapter
		 * @var string Config_IoAdapterType
		 */
		'Config_IoAdapterType' => 'fileobject',

		/**
		 * Execute_Type
		 *
		 * The type of execution for this module
		 * 		can be 'testing', 'development' or 'production' (default)(NOTE: Character case is important!!)
		 * @var string Execute_Type
		 */
		'Execute_Type' => 'testing',

		/**
		 * Exclude_Processes
		 *
		 * List of processes to be excluded, or empty for none
		 * @var string $Exclude_Processes
		 */
		'Exclude_Processes' => '',

		/**
		 * Include_Processes
		 *
		 * List of processes to be included, or 'all' for all processes (default)
		 * @var string $Include_Processes
		 */
		'Include_Processes' => 'all',

		/**
		 * Log_Format
		 *
		 * Log output format: none, log, timestamp (default), xml
		 * @var string Log_Format
		 */
		'Log_Format' => 'timestamp',

		/**
		 * Log_SubLog
		 *
		 * The configuration log sub-category to use for logging
		 * @var string Log_SubLog
		 */
		'Log_SubLog' => 'Launcher',

		/**
		 * Process_Script
		 *
		 * The name of the file containing the process scripts to run (default = standardTests)
		 * @var string Process_Script
		 */
		'Process_Script' => 'standardTests',

		/**
		 * Process_Type
		 *
		 * The type of process being executed ('Application' or 'Library')
		 * @var string Process_Type
		 */
		'Process_Type' => 'Library',

		/**
		 * Verbose
		 *
		 * Configuration verbose setting override - how verbose to be (0 = none, 1 = some, 2 or more = everything) (default=0)
		 * @var string Verbose
		 */
		'Verbose' => '',

		/* ************  Manually setable  ************ */

		/**
		 * eolSequence
		 *
		 * The end-of-line sequence
		 * @var string $eolSequence
		 */
		'Eol_Sequence' => "\r\n",

		/**
		 * timezone
		 *
		 * The timezone for this system/run
		 * @var string $timezone
		 */
		'timezone' => 'America/Denver',

	//
	// ********************************************************************************
	//
	//		Make no changes from here on
	//
	// ********************************************************************************
	//
	);

	/**
	 * optionMap
	 *
	 * An array which maps the option name to the properties name
	 * @var array $optionMap
	 */
	$optionMap = array(	'configadapter' 	=> 'Config_Adapter',
					   	'configfolder'		=> 'Config_Folder',
					   	'configio'			=> 'Config_IoAdapter',
					   	'configiotype'		=> 'Config_IoAdapterType',
					   	'configname'		=> 'Config_FileName',
						'execute'			=> 'Execute_Type',
					   	'exclude'			=> 'Exclude_Processes',
						'include'			=> 'Include_Processes',
						'root'				=> 'Install_Root',
						'logformat'			=> 'Log_Format',
						'logsub'			=> 'Log_SubLog',
						'process'			=> 'Process_Script',
						'type'				=> 'Process_Type',
						'verbose'			=> 'Verbose',
						);

	error_reporting(0);
	ini_set('display_errors', '0');

	if (! (include('../Utility/ProcessConfiguration.php')))
	{
		print "Unable to load ProcessConfiguration!\n";
		exit (1);
	}

	if (! (include('Main.php')))
	{
		print "Unable to load Main!\n";
		exit (1);
	}

	while (array_key_exists('argc', $_SERVER))
	{
		$argv = $_SERVER['argv'];
		if (array_key_exists('root', $argv))
		{
			$defaultProperties['Install_Root'] = $argv['root'];
		}

		break;
	}

	$launcher = new Main($defaultProperties, $optionMap);
	$result = $launcher->run($defaultProperties, $optionMap);

	exit($result);
