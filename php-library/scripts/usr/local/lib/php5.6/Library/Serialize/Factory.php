<?php
namespace Library\Serialize;

/*
 * 		Serialize\Factory is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * Serialize\Factory.
 *
 * Adapter factory class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Serialize.
 */
class Factory extends \Library\Factory
{
	/**
	 * newClasses
	 *
	 * An array containing class name look-up table.
	 * @var array $newClasses
	 */
	protected	  $newClasses 			= array('buffer'	=> 'Library\Serialize\Buffer',
												'file'		=> 'Library\Serialize\FileIO'
												);

	/**
	 * me
	 *
	 * Current class instance - if used, will be a singleton object
	 * @var object $me
	 */
	private			static	$me = null;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $addClasses = (optional) additional classes to add to the $classes array
	 */
	public function __construct($addClasses=array())
	{
		parent::__construct(array_merge($this->newClasses, $addClasses));
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
	 * constructFactory
	 *
	 * create an instance of the factory class
	 * @param array $addClasses = (optional) array containing class definitions
	 * @return Factory instance
	 */
	public static function constructFactory($addClasses=array())
	{
		if (! self::$me)
		{
			self::$me = new Factory($addClasses);
		}

		return self::$me;
	}

	/**
  	 * instantiateClass.
  	 *
  	 * This class factory function selects the proper class and instantiates it.
  	 * @param string $key = (optional) class key (default = 'buffer')
  	 * @return object $class = current class object instance.
	 * @throws FileIO\Exception
  	 */
  	public static function instantiateClass($key='buffer')
  	{

  		$me = self::constructFactory();

  		if (func_num_args() == 0)
  		{
  			throw new Exception(\Library\Error::code('FactoryMissingClassName'));
  		}

  		$args = func_get_args();
		try
		{
			return $me->instantiateArgs($args);
		}
		catch(\Library\Factory\Exception $exception)
		{
  			throw new Exception($exception->getMessage(), $exception->getCode());
		}
  	}

  	/**
  	 * getInstance
  	 *
  	 * Returns the current factory class instance, or null
  	 * @return object $instance
  	 */
	public static function getInstance()
	{
		return self::constructFactory();
	}

}
