<?php
namespace Servers\Soap\TVDBQuery;

use Servers\Utility\Exception as UtilityException;
use Servers\Utility\ServerConfiguration;

use Library\Exception\Descriptor as ExceptionDescriptor;

/*
 *		Servers\Soap\TVDBQuery\Main is copyright � 2015, 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Servers\Soap\TVDBQuery\Main
 *
 * Main program class for the TVDBQuery server application.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Servers
 * @subpackage Soap
 */
class Main extends ServerConfiguration
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
			$application = new Api($this->properties);
			$result = $application->process();
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
