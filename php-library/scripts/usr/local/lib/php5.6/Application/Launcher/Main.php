<?php
namespace Application\Launcher;

use Application\Launcher\Development\Exception as DevelopmentException;
use Application\Launcher\Exception;
use Application\Launcher\Factory;
use Application\Launcher\Production\Exception as ProductionException;
use Application\Launcher\Testing\Exception as TestingException;
use Application\Launcher\Utility\Exception as UtilityException;
use Application\Utility\ProcessConfiguration;
use Application\Utility\Support;
use Library\Exception\Descriptor as ExceptionDescriptor;

/*
 *		Application\Launcher\Main is copyright � 2015, 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\Launcher\Main
 *
 * Main program class for the launcher application.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage Launcher
 */
class Main extends ProcessConfiguration
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $properties = default properties array
	 * @param array $cliOptions = option to property mapping array
	 */
	public function __construct($defaults, $cliOptions)
	{
		parent::__construct($defaults, $cliOptions);
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
		Support::properties($this->properties);

		$result = 0;
		$descriptor = null;

		try
		{
			$factoryObject = $this->properties->Execute_Type;
			$runner = Factory::instantiateClass($factoryObject);
			$result = $runner->execute();
		}
		catch(Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(TestingException $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(DevelopmentException $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(ProductionException $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(StasticsException $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(UtilityException $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}

		if ($descriptor)
		{
			array_push($this->Exception_Descriptor, $descriptor);
			$result = $description->code;
		}

		return $result;
	}

}
