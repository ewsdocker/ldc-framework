<?php
namespace Servers\Soap\TVDBQuery;

use Library\SoapExt\SoapServer;

/*
 *		Servers\Soap\TVDBQuery\StartServer is copyright � 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	    Refer to the file named License.txt provided with the source,
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * StartServer
 *
 * Set up and start soap server
 * @author Jay Wheeler
 * @version 1.0
 * @phpversion 5.6.16
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Servers
 * @subpackage Soap
 */
class StartServer extends SoapServer
{
	/**
	 * requestString.
	 *
	 * A place to store the constructed request string.
	 * @var string
	 */
	private $requestString;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $defaults = default properties array
	 * @param array $cliOptions = option to property mapping array
	 * @throws Exception
	 */
	public function __construct($defaults, $cliOptions)
	{
		parent::__construct($defaults, $cliOptions);
		$this->properties = new TVDBProperties($this->properties, $defaults, $cliOptions);
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
  	 * Setup.
  	 *
  	 * Static function to setup the client environment.
     * @param boolean $production (optional) true => production (default), false => testing/development
     * @param array $testData = file name or array containing the test data ($production = false)
  	 */
  	public function Setup($production=true, $testData=null)
  	{
  		self::$production = $production;

		require_once('../../Utility/Support.php');
		self::$rootPath = Support::serverRoot();

		set_include_path(get_include_path() . PATH_SEPARATOR . self::$rootPath);

		if (self::$production)
		{
    		ini_set('soap.wsdl_cache_enabled', '1');
		}
		else
		{
	    	Common_PrintU::SelectInterface(Common_PrintU::INTERFACE_CONSOLE);

	    	Common_UserErrorHandler::ReportErrorTypes(Common_UserErrorHandler::rtAllErrors);

    		Common_UserErrorHandler::ReportUserErrors(true);
    		Common_UserErrorHandler::ReportDisplayErrors(true);

    		error_reporting(Common_UserErrorHandler::rtNoErrors);
    		set_error_handler('Common_UserErrorHandler::ErrorHandler');
    		error_reporting(Common_UserErrorHandler::rtAllErrors);

  	  		ini_set('soap.wsdl_cache_enabled', '0');

  	  		Common_CliParameters::Initialize($argc, $argv);

  	  		$testData = Common_CliParameters::parameterValue('in', $testData);
  	  		if (! Common_Autoload::LoadClass($testData))
  	  		{
  	  			Common_PrintU::PrintLine(sprintf("Autoload unable to load class %s\n", $testData));
  	  			exit(1);
  	  		}

  	  		Common_PrintU::PrintLine(sprintf("TestData = %s", $testData));
  	  		Common_PrintU::PrintLine('');

  	  		self::createRequestString(taxData_testData_FederalSet::$taxpayerArray);
		}

  	}

  	/**
  	 * RunServer.
  	 *
  	 * Static class to instantiate the SoapServer and start the server.
  	 * @return null
  	 */
  	public static function RunServer()
  	{
    	if (self::$production)
    	{
    		$taxEngine = new SoapServer('taxEngine.wsdl');
			$taxEngine->setClass('taxEngineServer_Server');
			$taxEngine->handle();
    	}
    	else
    	{
			$taxEngine = new taxEngineServer_Server();
    		$result = $taxEngine->Compute(self::$requestString);

    		foreach(taxData_testData_FederalSet::$taxpayerArray as $name => $value)
			{
		  		Common_PrintU::printLine(sprintf('  %s = %s', $name, $value));
			}

    		Common_PrintU::printLine("\n================================================================\n");

    		$result = explode('&', $result);
			foreach($result as $computeResult)
			{
		  		list($name, $value) = split('=', $computeResult);
		  		Common_PrintU::printLine(sprintf('  %s = %s', $name, $value));
			}
    	}
  	}

    /**
     * createRequestString.
     *
     * convert the taxData array to an HTML parameter string suitable for POST.
     * @param array $taxData associative array containing field-data pairs for conversion
     * @return null
     */
  	private static function createRequestString(&$taxData)
  	{
  	  	self::$requestString = '';
  	  	if (count($taxData) > 0)
	  	{
  	  		foreach($taxData as $name => $value)
  	  		{
  	  	  		if (get_magic_quotes_gpc())
  	  	  		{
  	  	    		$value = is_array($value) ? array_map('stripslashes_array', $value) :
	  	  	                                	stripslashes($value);
  	  	  		}

 	  	  		if (self::$requestString != '')
  	  	  		{
  	  	    		self::$requestString .= '&';
  	  	  		}

  	  	  		if (empty($value))
  	  	  		{
  	  	  			$value = 0;
  	  	  		}

	  	  		self::$requestString .= sprintf('%s=%s',
  	  	        	                        	$name,
  	  	                                       	$value);
  	  		}
  	  	}
  	}

}
