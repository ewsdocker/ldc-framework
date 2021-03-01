<?php
//namespace Library\SoapExt;

//use Library\SoapExt\Server as SoapServer;

/*
 *		ServersTests\Soap\SimpleServerTest is copyright � 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * ServersTests\Soap\SimpleServerTest
 *
 * Servers SOAP Server Test
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package ServersTests
 * @subpackage Soap
 */

	function SayHi($name)
	{
		return sprintf("Hi, %s\n", $name);
	}

	include('/usr/local/etc/PHPProjectLibrary/Application/Utility/ApplicationIncludes.php');
//	include('/usr/local/share/php/PHPProjectLibrary/Application/Utility/ApplicationIncludes.php');

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
		 * Server_Adapter
	 	 *
		 * The configuration file type adapter ('ini', 'xml', 'db')
		 * @var string Config_Adapter
		 */
		'Server_Adapter' => 'xml',

	   /**
		* Server_Folder
		*
		* The folder in the root path that contains the configuration file,
		*   or the absolute path (must begin with '/') to the configuration folder
		* @var string Config_Folder
		*/
		'Server_Folder' => 'Tests/SoapExt',

	   /**
		* Server_ConfigName
		*
		* The configuration file name (only - no file type)
		* @var string Server_FileName = the configuration file name.
		*/
		'Server_ConfigName' => 'SOAPControl',

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
		'Server_ConfigGroup' => 'SOAP',

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
			/* SOAP options */

			'soapadapter'	=> 'Soap_Adapter',
			'soapfolder'	=> 'Soap_Folder',
			'soapio'		=> 'Soap_IoAdapter',
			'soapiotype'	=> 'Soap_IoAdapterType',
			'soapconfig'	=> 'Soap_ConfigName',
			'soapgroup'		=> 'Soap_ConfigGroup',
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

		$applicationRoot = $defaultProperties['Install_Root'] . $defaultProperties['Server_Folder'];
		@chdir($defaultProperties['Install_Root'] . $defaultProperties['Server_Folder']);

		if (! (include('ServerConfiguration.php')))
		{
			print "Unable to load ProcessConfiguration!\n";
			exit (1);
		}

		if (! (include('Main.php')))
		{
			print "Unable to load Main!\n";
			exit (1);
		}






	$server = new SoapServer(null,
							 array('uri' 		  => 'urn://broadcastserver/SimpleServer',
							 	   'soap_version' => SOAP_1_2));

	$server->addFunction("SayHi");
	$server->handle();

