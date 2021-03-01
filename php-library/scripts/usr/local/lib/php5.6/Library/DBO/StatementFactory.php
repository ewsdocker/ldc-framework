<?php
namespace Library\DBO;

use Library\Error;

/*
 * 		DBO\StatementFactory is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DBO\StatementFactory
 *
 * DBO Driver Factory
 * @author Jay Wheeler.
 * @version 1.1
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DBO.
 */
class StatementFactory extends \Library\Factory
{
	/**
	 * newClasses
	 *
	 * An array containing class name look-up table.
	 * @var array $newClasses
	 */
	protected $newClasses = array('mysql'		=> 'Library\MySql\Statement',
								  'pdomysql'	=> 'Library\PDO\Statement',
								  );

	/**
	 * me
	 *
	 * Current class instance - if used, will be a singleton object
	 * @var object $me
	 */
	private	static $me = null;

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
	 * @throws Library\Factory\Exception
	 */
	public static function constructFactory($addClasses=array())
	{
		if (! self::$me)
		{
			self::$me = new StatementFactory($addClasses);
		}

		return self::$me;
	}

	/**
  	 * instantiateClass.
  	 *
  	 * This class factory function selects the proper class and instantiates it.
  	 * @param string $key = (optional) class key (default = stream)
  	 * @return object $class = current class object instance.
	 * @throws Library\DBO\Exception,Library\Factory\Exception
  	 */
  	public static function instantiateClass($key='mysql')
  	{
  		$me = self::constructFactory();

  		if (func_num_args() == 0)
  		{
  			throw new Exception(Error::code('FactoryMissingClassName'));
  		}

  		$args = func_get_args();
		return $me->instantiateArgs($args);
  	}

  	/**
  	 * getInstance
  	 *
  	 * return current factory class instance, or null if none exists
  	 * @return object $instance
  	 */
	public static function getInstance()
	{
  		return self::constructFactory();
	}

}
