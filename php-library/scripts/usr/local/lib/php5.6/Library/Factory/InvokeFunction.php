<?php
namespace Library\Factory;
use Library\Error;

/*
 * 		Factory\InvokeFunction is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * Factory\InvokeFunction.
 *
 * Call the requested function and pass any provided parameters.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Factory.
 */
class InvokeFunction
{
	/**
	 * function
	 *
	 * function name to invoke
	 * @var string $function
	 */
	protected		$functionName;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $functionName = (optional) function name
	 */
	public function __construct($functionName=null)
	{
		$this->functionName($functionName);
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 */
	public function __destruct()
	{
	}

  	/**
  	 * invoke.
  	 *
  	 * Invokes the requested function with arguments in a list
  	 * @param list $arguments = array with arguments to pass to the function,
  	 * @return mixed $result = function execution result, if appropriate
  	 * @throws Library\Factory\Exception, \ReflectionException
  	 */
	public function invoke()
	{
		$arguments = func_get_args();
		
		if (count($arguments) == 0)
		{
			throw new Exception(Error::code('MissingFunctionName'));
		}

		return $this->invokeArgs($arguments);
	}

  	/**
  	 * invokeArgs.
  	 *
  	 * Invokes the requested function with arguments
  	 * @param array $arguments = array of arguments to pass to the function
  	 * @return mixed $result = function execution result, if appropriate
  	 * @throws Library\Factory\Exception, \ReflectionException
  	 */
	public function invokeArgs(&$arguments)
	{
		if (! $this->functionName)
		{
			throw new Exception(Error::code('MissingFunctionName'));
		}

		$function = new \ReflectionFunction($this->functionName);
		$parameters = $function->getParameters();

		$elements = 0;
		if (count($arguments) > 0)
		{
			foreach($parameters as $key => $parameter)
			{
				if (! array_key_exists($key, $arguments))
				{
					break;
				}

				$elements++;
				if ($parameter->isPassedByReference())
				{
					$arguments[$key] = &$arguments[$key];
				}
			}
		}

		while (++$elements < count($arguments))
		{
			$arguments[$elements] = &$arguments[$elements];
		}

		$result = false;

		if (count($arguments) == 0)
		{
			$result = $function->invoke();
		}
		else
		{
			$result = $function->invokeArgs($arguments);
		}

		return $result;
	}

  	/**
  	 * functionName.
  	 *
  	 * get/set the function name.
  	 * @param string $functionName = (optional) function name, null to query.
  	 * @return string $functionName = function name.
  	 */
  	public function functionName($functionName=null)
  	{
  		if ($functionName)
  		{
  	  		$this->functionName = $functionName;
  		}

  		return $this->functionName;
  	}

}
