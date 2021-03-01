<?php
namespace Application\MCP\SOAP\TV;

/*
 *		MCP\SOAP\TV\serverindex is copyright � 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * MCP\SOAP\TV\serverindex
 *
 * MCP SOAP TV serverindex
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package MCP
 * @subpackage SOAP\TV
 */

	StartServer::Setup(true, 						    // production=true, development=false
					   'taxData_testData_FederalSet9'); // only used if development

	StartServer::RunServer();
