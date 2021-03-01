#!/usr/bin/php
<?php
namespace Application\TVDB;

#use Application\Utility\ProcessConfiguration;

/*
 *		TVDB is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * TVDB
 *
 * Cli program to control the TVDB interface
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 */

	include('/usr/local/share/php/PHPProjectLibrary/Application/Utility/ApplicationIncludes.php');

	/*
	 * Also supports the following TVDB flags:
	 *
	 *  applicationadapter = configuration file type adapter ('xml', 'ini', 'db')
	 *  applicationfolder = installation-root-based folder or absolute path to the configuration file folder (default = 'Library')
	 *  applicationio = configuration i/o adapter ('file', 'stream', ...) (default = 'file')
	 *  applicationiotype = configuration i/o adapter type ('fileobject', ...) (default = 'fileobject')
	 *  applicationconfig = configuration file name (no type) (default = 'launcherConfig')
	 *  applicationgroup = audio configuration group = 'TVDB')
	 *
	 */
	$defaultApplicationProperties = array(
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
		 * Application_ConfigGroup
		 *
		 * Configuration group name (default = TVDB)
		 * @var string Application_ConfigGroup
		 */
		'Application_ConfigGroup' => 'TVDB',

		/**
		 * Application_DbDriver
		 *
		 * Database Driver name
		 * @var string Application_DbDriver
		 */
		'Application_DbDriver'	  => 'mysql',

		/**
		 * Application_DbHost
		 *
		 * Database host name/address
		 * @var string Application_DbHost
		 */
		'DbHost' => '10.10.10.2',

		/**
		 * Application_DbPort
		 *
		 * Database host port number
		 * @var string Application_DbPort
		 */
		'DbPort' => '3306',

		/**
		 * Application_Db
		 *
		 * Database name
		 * @var string Application_Db
		 */
		'DbName' => 'TVDB',

		/**
		 * Application_DbUser
		 *
		 * Database User name
		 * @var string Application_DbUser
		 */
		'DbUser' => 'phplibuser',

		/**
		 * Application_DbPassword
		 *
		 * Database user password
		 * @var string Application_DbPassword
		 */
		'DbPassword' => 'phplibpwd',

	//
	// ********************************************************************************
	//
	//		Make no changes from here on
	//
	// ********************************************************************************
	//
	);

	/**
	 * applicationOptionMap
	 *
	 * An array which maps the option name to the properties name
	 * @var array $optionMap
	 */
	$applicationOptionMap = array(
						/* TVDB options */

						'applicationadapter'	=> 'Application_Adapter',
						'applicationfolder'		=> 'Application_Folder',
						'applicationio'			=> 'Application_IoAdapter',
						'applicationiotype'		=> 'Application_IoAdapterType',
						'applicationconfig'		=> 'Application_ConfigName',
						'applicationgroup'		=> 'Application_ConfigGroup',

						'host'					=> 'DbHost',
						'port'					=> 'DbPort',
						'dbdriver'				=> 'DbDriver',
						'db'					=> 'DbName',
						'dbuser'				=> 'DbUser',
						'dbpwd'					=> 'DbPassword',
						);

	$defaultProperties = array_merge($defaultProperties, $defaultApplicationProperties);
	$optionMap = array_merge($optionMap, $applicationOptionMap);

	error_reporting(0);
	ini_set('display_errors', '0');

	while (array_key_exists('argc', $_SERVER) && ($_Server['argc'] > 0))
	{
		$argv = $_SERVER['argv'];
		if (array_key_exists('root', $argv))
		{
			$defaultProperties['Install_Root'] = $argv['root'];
		}

		break;
	}

	$applicationRoot = $defaultProperties['Install_Root'] . $defaultProperties['Application_Folder'];
	@chdir($defaultProperties['Install_Root'] . $defaultProperties['Application_Folder']);

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

	$application = new Main($defaultProperties, $optionMap);
	$result = $application->run();

	exit($result);
