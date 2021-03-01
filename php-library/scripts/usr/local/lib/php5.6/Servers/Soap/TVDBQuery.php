#!/usr/bin/php
<?php
namespace Servers\Soap\TVDBQuery;

/*
 *		Servers\Soap\TVDBQuery\ is copyright � 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Servers\Soap\TVDBQuery
 *
 * Soap Server TVDBQuery interface
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Servers
 * @subpackage Soap
 */

	/*
	 * Also supports the following TVDBQuery flags:
	 *
	 *  serveradapter = configuration file type adapter ('xml', 'ini', 'db')
	 *  serverfolder  = installation-root-based folder or absolute path to the configuration file folder (default = 'Library')
	 *  serverio      = configuration i/o adapter ('file', 'stream', ...) (default = 'file')
	 *  serveriotype  = configuration i/o adapter type ('fileobject', ...) (default = 'fileobject')
	 *  serverconfig  = configuration file name (no type) (default = 'serverConfig')
	 *  servergroup   = configuration group = 'TVDBQuery')
	 *
	 */
	$defaultServerProperties = array(

		/**
		 * Server_Root
		 *
		 * TEMPORARY server installation root
		 * @var string $Server_Root
		 */
		'Server_Root' => '/var/www/html/Servers/',

		/**
		 * Server_Folder
		 *
		 * The folder in the server's root path that contains the configuration file,
		 *   or the absolute path (must begin with '/') to the configuration folder
		 * @var string Config_Folder
		 */
		'Server_Folder' => 'Soap/TVDBQuery',

		/**
		 * Server_ConfigName
		 *
		 * The configuration file name (only - no file type)
		 * @var string Server_ConfigName = the configuration file name.
		 */
		'Server_ConfigName' => 'TVDBQueryControl',

		/**
		 * Server_Adapter
		 *
		 * The configuration file type adapter ('ini', 'xml', 'db')
		 * @var string Server_Adapter
		 */
		'Server_Adapter' => 'xml',

		/**
		 * Server_IoAdapter
		 *
		 * The configuration I/O driver name ('file', 'stream')
		 * @var string Server_IoAdapter
		 */
		'Server_IoAdapter' => 'file',

		/**
		 * Server_IoAdapterType
		 *
		 * The type of I/O adapter
		 * @var string Server_IoAdapterType
		 */
		'Server_IoAdapterType' => 'fileobject',

		/**
		 * Server_ConfigGroup
		 *
		 * Configuration group name (default = TVDB)
		 * @var string Server_ConfigGroup
		 */
		'Server_ConfigGroup' => 'TVDBQuery',

	//
	// ********************************************************************************
	//
	//		Make no changes from here on
	//
	// ********************************************************************************
	//
	);

	/**
	 * serverOptionMap
	 *
	 * An array which maps the option name to the properties name
	 * @var array $optionMap
	 */
	$serverOptionMap = array(/* TVDBQuery options */

							 'serveradapter'	=> 'Server_Adapter',
							 'serverfolder'		=> 'Server_Folder',
							 'serverio'			=> 'Server_IoAdapter',
							 'serveriotype'		=> 'Server_IoAdapterType',
							 'serverconfig'		=> 'Server_ConfigName',
							 'servergroup'		=> 'Server_ConfigGroup',
							 );

	$defaultProperties = array_merge($defaultProperties, $defaultServerProperties);
	$optionMap = array_merge($optionMap, $serverOptionMap);

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

	$serverRoot = $defaultProperties['Server_Root'];
	$serverFolder = $serverRoot . $defaultProperties['Server_Folder'];

	@chdir($serverFolder);

	if (! (include('../../Utility/ServerConfiguration.php')))
	{
		print "Unable to load Utility/ServerConfiguration!\n";
		exit (1);
	}

	$serverProperties = new ServerConfiguration($defaultProperties, $optionMap);

	if (! (include('Server.php')))
	{
		print "Unable to load TVDBQuery/Server!\n";
		exit (1);
	}

	$server = new Server($defaultProperties, $optionMap);
	$result = $server->run();

	exit($result);
