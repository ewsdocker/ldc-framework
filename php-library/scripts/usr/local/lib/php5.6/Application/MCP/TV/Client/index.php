<?php
/*
 	taxEngineClient_index is copyright � 2008, RushTax, Inc.

	This application is free software; you can redistribute it and/or
	modify it under the terms of the GNU Lesser General Public
	License as published by the Free Software Foundation; either
	version 3 of the License, or (at your option) any later version.

	This library is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	Lesser General Public License for more details.

	You should have received a copy of the GNU Lesser General Public
	License along with this library; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/**
 * taxEngineClient_index.
 *
 * Client entry point to the taxEngine SOAP server.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2008, 2009. RushTax, Inc.
 * @package taxEngine
 * @subpackage taxEngineClient
 */

// **************************************************************************************

	define ('TAXENGINE_PRODUCTION', true);

	require_once('Client.php');

	if (! TAXENGINE_PRODUCTION)
	{
		$_POST['development'] = 'taxData_testData_FederalSet9';
	}

	taxEngineClient_Client::Setup($_POST,
								  'http://fedora10.ewdesigns.lan/taxEngineClient/index.php',
								  'http://fedora10.ewdesigns.lan/taxEngineServer/taxEngine.wsdl',
								  TAXENGINE_PRODUCTION);

	taxEngineClient_Client::RunEngine();

    exit(0);
