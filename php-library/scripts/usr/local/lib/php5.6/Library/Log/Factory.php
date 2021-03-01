<?php
namespace Library\Log;

/*
 * 		Log\Factory is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 *      Refer to the file named License provided with the source.
 */
/**
 * Log\Factory.
 *
 * Log\Factory class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Log.
 */
class Factory extends \Library\Factory
{
	/**
	 * valid
	 *
	 * Validity flag
	 * @var boolean $valid
	 */
	private			$valid;

	/**
	 * newClasses
	 *
	 * An array containing class name look-up table.
	 * @var array $newClasses
	 */
	protected	  	$newClasses	= array('fileio'   	=> 'Library\Log\FileIO',
										'print'		=> 'Library\Log\Printer',
										'printu'	=> 'Library\Log\PrintU',
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
		$this->valid = false;
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 * @return null
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
	 * destructFactory
	 *
	 * destroy this instance of the factory class
	 * @return null
	 */
	public static function destructFactory()
	{
		if (self::$me)
		{
			self::$me = null;
		}

	}

	/**
  	 * instantiateClass.
  	 *
  	 * This class factory function selects the proper class and instantiates it.
  	 * @param string $key = (optional) class key (default = print)
  	 * @return object $class = current class object instance.
	 * @throws \Library\Log\Exception, \Library\FileIO\Exception
  	 */
  	public static function instantiateClass($key='print')
  	{
  		$me = self::constructFactory();

  		if (func_num_args() == 0)
  		{
  			throw new \Library\Log\Exception(\Library\Error::code('MissingClassName'));
  		}

  		$args = func_get_args();
		$instance = $me->instantiateArgs($args);
		$me->valid = true;
		
		return $instance;
  	}

  	/**
  	 * isValid
  	 *
  	 * Query the validity flag, returns true if it is valid
  	 * @return boolean $valid
  	 */
	public function isValid()
	{
		return $this->valid;
	}

}
