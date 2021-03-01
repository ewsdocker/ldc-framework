<?php
namespace Application\MCP\SOAP\TV;

/*
 *		MCP\SOAP\TV\StartServer is copyright � 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * MCP\SOAP\TV\StartServer
 *
 * MCP SOAP TV Start Server
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package MCP
 * @subpackage SOAP\TV
 */
class StartServer
{
	/**
	 * rootPath.
	 *
	 * Path to the installation root (..)
	 * @var string
	 */
	private static	$rootPath = '';

	/**
	 * production.
	 *
	 * Production code = true (default). Set to false for debug.
	 * @var boolean
	 */
	private static	$production = false;

	/**
	 * requestString.
	 *
	 * A place to store the constructed request string.
	 * @var string
	 */
	private static	$requestString = '';

  	/**
  	 * Setup.
  	 *
  	 * Static function to setup the client environment.
     * @param boolean $production (optional) true=production, false=development
     * @param array $testData Federal test data file name
  	 * @return null
  	 */
  	public static function Setup($production=true, $testData=null)
  	{
  		self::$production = $production;

		require_once('../Common/Autoload.php');
		self::$rootPath = Common_Autoload::AutosetRoot();

		set_include_path(get_include_path() . PATH_SEPARATOR . self::$rootPath);

  	  	Common_Autoload::LoadClass('taxEngineServer_Server');

		if (self::$production)
		{
    		ini_set('soap.wsdl_cache_enabled', '1');
		}
		else
		{
	    	PrintU::SelectInterface(Common_PrintU::INTERFACE_CONSOLE);

	    	UserErrorHandler::ReportErrorTypes(Common_UserErrorHandler::rtAllErrors);

    		UserErrorHandler::ReportUserErrors(true);
    		UserErrorHandler::ReportDisplayErrors(true);

    		error_reporting(UserErrorHandler::rtNoErrors);
    		set_error_handler('UserErrorHandler::ErrorHandler');
    		error_reporting(UserErrorHandler::rtAllErrors);

  	  		ini_set('soap.wsdl_cache_enabled', '0');

  	  		CliParameters::Initialize($argc, $argv);

  	  		$testData = CliParameters::parameterValue('in', $testData);
  	  		if (! Autoload::LoadClass($testData))
  	  		{
  	  			PrintU::PrintLine(sprintf("Autoload unable to load class %s\n", $testData));
  	  			exit(1);
  	  		}

  	  		PrintU::PrintLine(sprintf("TestData = %s", $testData));
  	  		PrintU::PrintLine('');

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
    		$tvSoap = new SoapServer('tvSoap.wsdl');
			$tvSoap->setClass('MCP\SOAP\TV\Server');
			$tvSoap->handle();
    	}
    	else
    	{
			$tvSoap = new Server();
    		$result = $tvSoap->Compute(self::$requestString);

    		$result = explode('&', $result);
			foreach($result as $computeResult)
			{
		  		list($name, $value) = split('=', $computeResult);
		  		PrintU::printLine(sprintf('  %s = %s', $name, $value));
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
