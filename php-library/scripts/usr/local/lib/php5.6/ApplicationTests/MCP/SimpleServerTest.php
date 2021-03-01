<?php
namespace \usr\local\share\php\PHPProjectLibrary\Library\SOAP;

use \usr\local\share\php\PHPProjectLibrary\Library\SOAP\Server as SoapServer;

/*
 *		MCP\SimpleServerTest is copyright � 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * MCP\SimpleServerTest
 *
 * MCP SOAP Server Test
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package MCP
 * @subpackage SOAP
 */

	function SayHi($name)
	{
		return sprintf("Hi, %s\n", $name);
	}

	$server = new SoapServer(null,
							 array('uri' 		  => 'urn://broadcastserver/SimpleServer',
							 	   'soap_version' => SOAP_1_2));

	$server->addFunction("SayHi");
	$server->handle();

