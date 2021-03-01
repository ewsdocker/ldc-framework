#!/usr/bin/php
<?php
namespace Application\jackAudioControl;

#use Application\Utility\ProcessConfiguration;

/*
 *		jackAudioControl is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * jackAudioControl
 *
 * Cli program to control the jack audio connections kit, allowing start, stop, restart, ...
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package jackAudioControl
 */

	include('/usr/local/include/php/PHPProjectLibrary/Application/Utility/ApplicationIncludes.php');

	/*
	 * Also supports the following jackAudioControl flags:
	 *
	 *  jackadapter = configuration file type adapter ('xml', 'ini', 'db')
	 *  jackfolder = installation-root-based folder or absolute path to the configuration file folder (default = 'Library')
	 *  jackio = configuration i/o adapter ('file', 'stream', ...) (default = 'file')
	 *  jackiotype = configuration i/o adapter type ('fileobject', ...) (default = 'fileobject')
	 *  jackconfig = configuration file name (no type) (default = 'launcherConfig')
	 *  jackgroup = audio configuration group = 'Audio')
	 *  jacksystem = audio system (default = 'Radio')
	 *  jackstartwait = wait time (sec + nano) between start commands
	 *  jackstopwait = wait time between stop commands
	 *
	 */
	$defaultJackProperties = array(
		/**
		 * Jack_Adapter
		 *
		 * The configuration file type adapter ('ini', 'xml', 'db')
		 * @var string Config_Adapter
		 */
		'Jack_Adapter' => 'xml',

		/**
		 * Jack_Folder
		 *
		 * The folder in the root path that contains the configuration file,
		 *   or the absolute path (must begin with '/') to the configuration folder
		 * @var string Config_Folder
		 */
		'Jack_Folder' => 'Application/jackAudioControl',

		/**
		 * Jack_ConfigName
		 *
		 * The configuration file name (only - no file type)
		 * @var string Jack_FileName = the configuration file name.
		 */
		'Jack_ConfigName' => 'AudioControl',

		/**
		 * Jack_IoAdapter
		 *
		 * The configuration I/O driver name ('file', 'stream')
		 * @var string Jack_IoAdapter
		 */
		'Jack_IoAdapter' => 'file',

		/**
		 * Jack_IoAdapterType
		 *
		 * The type of I/O adapter
		 * @var string Jack_IoAdapterType
		 */
		'Jack_IoAdapterType' => 'fileobject',

		/**
		 * Jack_ConfigGroup
		 *
		 * Configuration group name (default = Audio)
		 * @var string Jack_ConfigGroup
		 */
		'Jack_ConfigGroup' => 'Audio',

		/**
		 * Jack_AudioSystem
		 *
		 * Audio configuration system (default = Radio)
		 */
		'Jack_AudioSystem' => 'Radio',

		/**
		 * Jack_StartWait
		 *
		 * Audio configuration system (default = Radio)
		 */
		'Jack_StartWait' => '4',

		/**
		 * Jack_StopWait
		 *
		 * Audio configuration system (default = Radio)
		 */
		'Jack_StopWait' => '2',

	//
	// ********************************************************************************
	//
	//		Make no changes from here on
	//
	// ********************************************************************************
	//
	);

	/**
	 * jackOptionMap
	 *
	 * An array which maps the option name to the properties name
	 * @var array $optionMap
	 */
	$jackOptionMap = array(
						/* jackAudioControl options */

						'jackadapter'		=> 'Jack_Adapter',
						'jackfolder'		=> 'Jack_Folder',
						'jackio'			=> 'Jack_IoAdapter',
						'jackiotype'		=> 'Jack_IoAdapterType',
						'jackconfig'		=> 'Jack_ConfigName',

						'jackgroup'			=> 'Jack_ConfigGroup',
						'jacksystem'		=> 'Jack_AudioSystem',
						'jackstartwait'		=> 'Jack_StartWait',
						'jackstopwait'		=> 'Jack_StopWait,'

						);

	$defaultProperties = array_merge($defaultProperties, $defaultJackProperties);
	$optionMap = array_merge($optionMap, $jackOptionMap);

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

	$applicationRoot = $defaultProperties['Install_Root'] . $defaultProperties['Jack_Folder'];
	@chdir($defaultProperties['Install_Root'] . $defaultProperties['Jack_Folder']);

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
