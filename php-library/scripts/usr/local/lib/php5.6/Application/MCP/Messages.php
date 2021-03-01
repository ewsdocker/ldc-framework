<?php
namespace Application\MCP;

use Library\Error;
use Library\Error\AddMessages;

/*
 *		Application\MCP\Messages is copyright � 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\MCP\Messages.
 *
 * The Application\MCP\Messages class initializes the custom error codes and associated messages for the Application\MCP class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage MCP
 */
class Messages extends AddMessages
{
	/**
	 * errorList
	 *
	 * Error name to error message array
	 * @var array $errorList
	 */
	private	$errorList =
			  array('MCP_Unknown'			=> 'Unknown or unspecified MCP error',
					);

	/**
	 * __construct
	 *
	 * Class constructor
	 * Register custom error messages with the error code/message system
	 * @param array $additionalErrors = (optional) additonal error messages array
	 */
	public function __construct($additionalErrors=null)
	{
		if (($additionalErrors !== null) && is_array($additionalErrors))
		{
			$this->errorList = array_merge($this->errorList, $additionalErrors);
		}

		parent::__construct('MCP', $this->errorList);
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

}
