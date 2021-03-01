<?php
namespace Library\Factory;
use Library\Error;

/*
 * 		Factory\InstantiateClass is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * Factory\InstantiateClass.
 *
 * Instantiate the requested class and pass the provided parameters to the class constructor, if it exists
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Factory.
 */
class InstantiateClass
{
	/**
	 * className
	 *
	 * name of the class ($classes[$name] => $className)
	 * @var string $className
	 */
	protected		$className;

	/**
	 * class
	 *
	 * instantiated class object
	 * @var object $class
	 */
	protected		$class;

	/**
	 * __construct
	 *
	 * Class constructor
	 */
	public function __construct()
	{
		$this->class = null;
		$this->className = null;
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 * @return null
	 */
	public function __destruct()
	{
	}

  	/**
  	 * instantiate.
  	 *
  	 * Instantiates the requested class with arguments
  	 * @param string $name = (optional) class name.
  	 * @throws Factory_Exception
  	 */
	public function instantiate()
	{
		$args = func_get_args();
		return $this->instantiateArgs($args);
	}

  	/**
  	 * instantiateArgs.
  	 *
  	 * Instantiates the requested class with arguments
  	 * @param array $arguments = reference to the array of arguments to pass to the class
  	 * @return object $class
  	 * @throws Factory\Exception, \ReflectionException
  	 */
	public function instantiateArgs(&$arguments)
	{
		if (count($arguments) == 0)
		{
			$className = $this->className();
		}
		else
		{
			$className = $this->className(array_shift($arguments));
		}

		if (! $className)
		{
			throw new Exception(Error::code('MissingClassName'));
		}

		if (! class_exists($className, false))
		{
			if (! \Library\Autoload::loadClass($className))
			{
				throw new Exception(Error::code('AutoloadFailed'));
			}
		}

		$class = new \ReflectionClass($className);

		$constructor = true;
		if (($method = $class->getConstructor()) === null)
		{
			try 
			{
				$method = new \ReflectionMethod($class, '__construct');
				$constructor = true;
			}
			catch(\ReflectionException $exception)
			{
				$constructor = false;
			}

			if (! $constructor)
			{
				$names = explode('\\', $className);
				$name = array_pop($names);
				try 
				{
					$method = $class->getMethod($name);
					$constructor = true;
				}
				catch(\ReflectionException $exception)
				{
					$constructor = false;
				}
			}
		}

		if (! $constructor)
		{
			if (count($arguments) > 0)
			{
				throw new Exception(Error::code('NoClassConstructor'));
			}
			
			if (method_exists($class, 'newInstanceWithoutConstructor'))
			{
				return $class->newInstanceWithoutConstructor();
			}

			return $this->newInstanceWithoutConstructor($class, $className);
		}

		if (count($arguments) > 0)
		{
			$parameters = $method->getParameters();

			foreach($parameters as $key => $parameter)
			{
				if (! array_key_exists($key, $arguments))
				{
					break;
				}

				if ($parameter->isPassedByReference())
				{
					$arguments[$key] = &$arguments[$key];
				}
			}

			while (++$key < count($arguments))
			{
				$arguments[$key] = &$arguments[$key];
			}
		}

		$this->class = $class->newInstanceArgs($arguments);

		return $this->class;
	}

	/**
  	 * classInstance
  	 *
  	 * Return the instance object of className
  	 * @returns $class = current instance object
  	 */
  	public function classInstance()
  	{
  		return $this->class;
  	}

  	/**
  	 * className.
  	 *
  	 * get/set the class instance name.
  	 * @param string $className = (optional) class name, null to query.
  	 * @return string $className = class name.
  	 */
  	public function className($className=null)
  	{
  		if ($className)
  		{
  	  		$this->className = $className;
  		}

  		return $this->className;
  	}

  	/**
  	 * newInstanceWithoutConstructor
  	 *
  	 * Create a new instance of a class without a constuctor (__construct)
  	 * @param object $class = ReflectionClass object to create an instance of
  	 * @param string $className = name of the class being created
  	 */
  	private function newInstanceWithoutConstructor($class, $className)
  	{
  		$properties = $class->getProperties();
  		$defaults = $class->getDefaultProperties();
  		
  		$serialized = sprintf('O:%u:"%s":%u:{', strlen($className), $className, count($properties));

  		foreach($properties as $property)
  		{
  			$name = $property->getName();

  			if ($property->isProtected())
  			{
  				$name = chr(0) . '*' . chr(0) . $name;
  			}
  			elseif ($property->isPrivate())
  			{
  				$name = chr(0) . $className . chr(0) . $name;
  			}
  			
  			$serialized .= serialize($name);

  			if (array_key_exists($name, $defaults))
  			{
  				$serialized .= serialize($defaults[$name]);
  			}
  			else
  			{
  				$serialized .= serialize(null);
  			}
  		}
  		
  		$serialized .= '}';

  		return unserialize($serialized);
  	}

}
