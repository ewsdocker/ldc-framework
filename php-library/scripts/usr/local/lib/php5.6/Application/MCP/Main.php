<?php
namespace Application\MCP;

use Application\Utility\Exception as UtilityException;
use Application\Utility\ProcessConfiguration;

use Library\Exception\Descriptor as ExceptionDescriptor;

/*
 *		Application\MCP\Main is copyright � 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\MCP\Main
 *
 * Main program class for the MCP application.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage MCP
 */
class Main extends ProcessConfiguration
{
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

		$errorMessages = new Messages();

		$this->properties = new MCPProperties($this->properties, $defaults, $cliOptions);
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
	 * run
	 *
	 * Run the program
	 * @return integer $result
	 */
	public function run()
	{
		$result = 0;
		$descriptor = null;

		try
		{
//			$application = new Api($this->properties);
//			$result = $application->process();
		}
		catch(Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(UtilityException $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}

		if ($descriptor !== null)
		{
			array_push($this->Exception_Descriptor, $descriptor);
			$result = $description->code;
		}

		return $result;
	}

}
