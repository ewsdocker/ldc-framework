<?php
namespace Application\jackAudioControl;

use Application\jackAudioControl\JackProperties;

use Application\jackAudioControl\Exception;
use Application\jackAudioControl\Process;

use Application\Utility\ProcessConfiguration;
use Application\Utility\Support;
use Application\Utility\Exception as UtilityException;

use Library\Exception\Descriptor as ExceptionDescriptor;
use Library\PrintU;

/*
 *		Application\jackAudioControl\Main is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\jackAudioControl\Main
 *
 * Main program class for the jackAudioControl application.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage jackAudioControl
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
		$this->properties = new JackProperties($this->properties, $defaults, $cliOptions);
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
			$process = new Process($this->properties);
			$process->loadProcesses();
			$result = $process->execute();
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
