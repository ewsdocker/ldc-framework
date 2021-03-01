#!/usr/bin/php
<?php

// namespace \usr\local\share\php\PHPProjectLibrary\Library\SOAP;
// use Server as SoapServer;

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

	$client = new SoapClient(null,
							 array('location'		=> "http://10.10.10.5/SimpleServerTest.php",
								   'uri' 			=> 'urn://10.10.10.5/SimpleServer',
								   'soap_version'	=> SOAP_1_2,
								   'trace'			=> 1 ));

   $return = $client->__soapCall("SayHi", array("Jay"));
   echo("\nReturning value of __soapCall() call: ".$return);

   echo("\nDumping request headers:\n"
      .$client->__getLastRequestHeaders());

   echo("\nDumping request:\n".$client->__getLastRequest());

   echo("\nDumping response headers:\n"
      .$client->__getLastResponseHeaders());

   echo("\nDumping response:\n".$client->__getLastResponse());
?>