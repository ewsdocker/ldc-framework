<?php
namespace Library\SoapExt;

#use \SoapServer as SoapServer;

/*
 *		SoapExt\Server is copyright � 2012, 2016. EarthWalk Software.
 *		Licensed under the Academic Free License version 3.0.
 *		Refer to the file named License.txt provided with the source,
 *			  or from http://opensource.org/licenses/academic.php
 */
/**
 * Library\SoapExt\Server.
 *
 * Extended PHP SoapServer class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage SoapExt
 */
class Server extends SoapServer
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $wsdl = (optional) location of wsdl file, null to not use one.
	 * @param array $options = (optional) array of options, null for none.
	 * @throws Library\SoapExt\Exception on SoapServer::__construct fault
	 */
	public function __construct($wsdl=null, $options=null)
	{
		new MapFault();
		parent::__construct($wsdl, $options);
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
	 * fault
	 *
	 * Override the SoapServer::fault function to re-throw without exiting the application
	 * @param string $code = The error code to return
	 * @param string $string = A brief description of the error
	 * @param string $actor = (optional) A string identifying the actor that caused the fault.
	 * @param string $details = (optional) More details of the fault
	 * @param string $name = (optional)The name of the fault. This can be used to select a name from a WSDL file.
	 * @throws SoapFault
	 */
	public function fault($code, $string, $actor=null, $details=null, $name=null)
	{
		throw new SoapFault($code, $string, $actor, $details, $name);
	}

}
