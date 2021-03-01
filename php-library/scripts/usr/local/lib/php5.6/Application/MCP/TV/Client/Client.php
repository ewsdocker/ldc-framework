<?php
namespace Application\MCP\TV\Client;

/*
 *		MCP\TV\Client\Server is copyright � 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * MCP\TV\Client\Server
 *
 * MCP SOAP TV Client
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TV
 * @subpackage SOAP
 */
class Client
{
	/**
	 * taxpayer information.
	 * @var array[string]mixed
	 */
	private static 	$taxpayerArray 		= array();

	/**
	 * The taxpayer array in string form.
	 * @var string
	 */
	private	static	$requestString		= '';

	/**
	 * The number of fields in the taxpayer array.
	 * @var integer
	 */
	private static	$requestFields  	= 0;

	/**
	 * Exception message.
	 * @var string
	 */
	private static	$exception			= '';

	/**
	 * Production flage.  true = production, false = development.
	 * @var boolean
	 */
	private static	$production			= true;

	/**
	 * Proxy server address, if not null.
	 * @var string
	 */
	private static 	$proxyServer		= null;

	/**
	 * Proxy server port.
	 * @var string
	 */
	private static 	$proxyPort			= '8080';

	/**
	 * The computed rootPath for the server.
	 * @var string
	 */
	private static	$rootPath			= '.';

	/**
	 * The callback url (submit button).
	 * @var string
	 */
	private static	$taxformURL			= '';

	/**
	 * The url of the taxEngineServer wsdl.
	 * @var string
	 */
	private static	$taxengineWSDL		= '';

	/**
  	 * Setup.
  	 *
  	 * Static function to setup the client environment.
     * @param array $taxData contains taxpayer data as field-value pairs
     * @param string $taxformURL contains url of the tax input form (assigned to Submit button)
     * @param string $taxengineWSDL contains url of the taxEngineServer WSDL file
     * @param boolean $production (optional) true=production, false=development
     * @param string $proxyServer (optional) contains address of the proxy server, if not null
     * @param string $proxyPort (optional) contains proxy server port
  	 * @return null
  	 */
  	public static function Setup(&$taxData, $taxformURL, $taxengineWSDL, $production=true, $proxyServer=null, $proxyPort='8080')
  	{
  		self::$production = $production;

	  	self::$proxyServer = $proxyServer;
	  	self::$proxyPort = $proxyPort;

	  	self::$taxformURL = $taxformURL;
	  	self::$taxengineWSDL = $taxengineWSDL;

		/**
		 * An associative array which contains the individual components of the SCRIPT_FILENAME.
		 * @var array[string]string $dirs
		 */
		$altdirs = false;

	  	$dirs = array();

   		if ((! $dirs = explode(PATH_SEPARATOR, $_SERVER['SCRIPT_FILENAME'])) ||
   			(count($dirs) < 2))
   		{
   			if ((! $dirs = explode('/', $_SERVER['SCRIPT_FILENAME'])) ||
   			    (count($dirs) < 2))
   			{
   				$altdirs = true;
   				if (! $dirs = explode('/', dirname(__FILE__)))
   				{
   					$dirs[0] = '.';
   					$dirs[1] = '.';
   				}
   			}
   		}

		array_pop($dirs);
   		if (! $altdirs)
   		{
			array_pop($dirs);
   		}

	   	self::$rootPath = implode('/', $dirs);

		require_once (sprintf('%s/%s', self::$rootPath, 'Common/Autoload.php'));

		Common_Autoload::InstallRoot(self::$rootPath);
		set_include_path(get_include_path() . PATH_SEPARATOR . self::$rootPath);

		if (self::$production)
		{
    		ini_set('soap.wsdl_cache_enabled', '1');
	  		self::createRequestString($taxData);
		}
		else
		{
	    	Common_PrintU::SelectInterface(Common_PrintU::INTERFACE_CONSOLE);

    		Common_UserErrorHandler::ReportUserErrors(true);
    		Common_UserErrorHandler::ReportDisplayErrors(true);

    		error_reporting(Common_UserErrorHandler::rtNoErrors);
    		set_error_handler('Common_UserErrorHandler::ErrorHandler');
    		error_reporting(Common_UserErrorHandler::rtAllErrors);

  	  		ini_set('soap.wsdl_cache_enabled', '0');

  	  		Common_CliParameters::Initialize($argc, $argv);

			$testData = '';
  	  		if (array_key_exists('development', $taxData))
			{
				$testData = $taxData['development'];
			}

  	  		$testData = Common_CliParameters::parameterValue('in', $testData);
  	  		if (! Common_Autoload::LoadClass($testData))
  	  		{
  	  			Common_PrintU::PrintLine(sprintf("Autoload unable to load class %s\n", $testData));
  	  			exit(1);
  	  		}

  	  		Common_PrintU::PrintLine(sprintf("TestData = %s", $testData));
  	  		Common_PrintU::PrintLine('');

  	  		self::createRequestString(taxData_testData_FederalSet::$taxpayerArray);
  	  		self::$taxpayerArray['submit'] = 'submit';
		}

  	}

    /**
     * RunEngine.
     *
     * Send the request to the SOAP server to process and send the result in HTML
     * output page.
     * @return boolean true=successful, false=unable to complete
     */
  	public static function RunEngine()
	{
		self::$exception = '';

		if (! array_key_exists('submit', self::$taxpayerArray))
  	  	{
			$clientView = new taxEngineClient_ClientView(self::$taxformURL);

  	  		$clientView->formatInputForm(false, array());
	  		$clientView->sendPageBuffer(false, false);
	  		return true;
	  	}

	  	// *****************************************************************************

  	  	if (self::$proxyServer != null)
  	  	{
  			$client = new SoapClient(self::$taxengineWSDL,
  								     array('proxy_host' => self::$proxyServer,
                                   	       'proxy_port' => self::$proxyPort));
  	  	}
  	  	else
  	  	{
  			$client = new SoapClient(self::$taxengineWSDL);
  	  	}

	  	// *****************************************************************************

  	  	$taxResult = '';
	  	try
	  	{
	    	$taxResult = $client->compute(self::$requestString);
	  	}
	  	catch(SoapFault $exception)
	  	{
  			self::$exception .= sprintf('EXCEPTION: %s', $exception);
  			return false;
	  	}

	  	unset($client);

	  	// *****************************************************************************

		$clientView = new taxEngineClient_ClientView(self::$taxformURL);

		if (self::$production)
		{
	  		$clientView->formatInputForm(self::$taxpayerArray);
	  		$clientView->formatTaxEngineForm($taxResult);
	  		$clientView->sendPageBuffer();
		}
		else
		{
			Common_PrintU::PrintLine('');
    		foreach(self::$taxpayerArray as $name => $value)
			{
		  		Common_PrintU::PrintLine(sprintf('  %s = %s', $name, $value));
			}

    		Common_PrintU::PrintLine("\n================================================================\n");

    		$result = explode('&', $taxResult);
			foreach($result as $computeResult)
			{
		  		list($name, $value) = split('=', $computeResult);
		  		Common_PrintU::PrintLine(sprintf('  %s = %s', $name, $value));
			}

		}
	  	return true;
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
  	  	self::$taxpayerArray = array();
  	  	self::$requestString = '';
  	  	self::$requestFields = count($taxData);
  	  	if (self::$requestFields > 0)
	  	{
  	  		foreach($taxData as $name => $value)
  	  		{
  	  	  		if (get_magic_quotes_gpc())
  	  	  		{
  	  	    		$value = is_array($value) ? array_map('stripslashes_array', $value) :
	  	  	                                	stripslashes($value);
  	  	  		}

  	  	  		self::$taxpayerArray[strtolower($name)] = $value;

	  	  		if ($name == 'submit')
  	  	  		{
  	  	  			continue;
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
