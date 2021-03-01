<?php
namespace Library\Factory;
use Library\Error;

/*
 * 		Factory\InvokeClassMethod is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * Factory\InvokeClassMethod.
 *
 * Call the requested class method and pass any provided parameters.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Factory.
 */
class InvokeClassMethod
{
	/**
	 * instance
	 *
	 * instantiated class object
	 * @var object $instance
	 */
	protected		$instance;

	/**
	 * method
	 *
	 * Instance method name to invoke
	 * @var string $method
	 */
	protected		$method;

	/**
	 * __construct
	 *
	 * Class constructor
  	 * @param object  $instance = class instance object
  	 * @param string  $method = name of the class method to invoke
	 */
	public function __construct($instance=null, $method=null)
	{
		$this->method($method);
		$this->instance($instance);
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
  	 * Invokes the requested class method with arguments in a list.
  	 * @param array $arguments = array with class instance object in element 0, and arguments to pass to the class,
  	 * @return mixed $result = function execution result, if appropriate
  	 * @throws Library\Factory\Exception, \ReflectionException
  	 */
	public function invoke()
	{
		$arguments = func_get_args();

		if (count($arguments) == 0)
		{
			throw new Exception(Error::code('MissingClassObject'));
		}

		$this->instance = array_shift($arguments);

		return $this->invokeArgs($arguments);
	}

  	/**
  	 * invokeArgs.
  	 *
  	 * Invokes the requested class method with arguments in an array.
  	 * Sets pass-by-reference if required by method parameters, leaves unmodified if not.
  	 * @param array $arguments = array of arguments to pass to the class method
  	 * @return mixed $result = function execution result, if appropriate
  	 * @throws Library\Factory\Exception, \ReflectionException
  	 */
	public function invokeArgs(&$arguments)
	{
		if ((! $this->instance) || (! is_object($this->instance)))
		{
			throw new Exception(Error::code('MissingClassObject'));
		}

		if (! $this->method)
		{
			throw new Exception(Error::code('MissingClassMethod'));
		}

		$method = new \ReflectionMethod($this->instance, $this->method);
		$parameters = $method->getParameters();

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

		if (count($arguments) == 0)
		{
			$result = $method->invoke($this->instance);
		}
		else
		{
			$result = $method->invokeArgs($this->instance, $arguments);
		}

		return $result;
	}

  	/**
  	 * method.
  	 *
  	 * get/set the method name.
  	 * @param string $methodName = (optional) class method name, null to query.
  	 * @return string $methodName = class method name.
  	 */
  	public function method($methodName=null)
  	{
  		if ($methodName)
  		{
  	  		$this->method = $methodName;
  		}

  		return $this->method;
  	}

  	/**
  	 * instance
  	 *
  	 * get/set the instance object
  	 * @param object $instance = (optional) class instance object, null to query
  	 * @return object $instance = current instance object
  	 * @throws Library\Factory\Exception
  	 */
  	public function instance($instance = null)
  	{
  		if ($instance !== null)
  		{
  			if (! is_object($instance))
  			{
  				throw new Exception(Error::code('MissingClassObject'));
  			}

  			$this->instance = $instance;
  		}

  		return $this->instance;
  	}

  	/**
  	 * instanceName.
  	 *
  	 * get the instance name of the instance object.
  	 * @return string|null $instanceName = class name, null if class is invalid or unset.
  	 */
  	public function instanceName()
  	{
  		if ($this->instance && (is_object($this->instance)))
  		{
  			return get_class($this->instance);
  		}

  		return null;
  	}

}
