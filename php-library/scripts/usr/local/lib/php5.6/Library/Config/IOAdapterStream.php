<?php
namespace Library\Config;
use Library\Error;

/*
 * 		Config\AdapterStreamIO is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Config\AdapterStreamIO.
 *
 * Adapter to interface to the Stream adapter
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Config.
 */
class AdapterStreamIO extends AdapterAbstract
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $adapterName = (optional) adapterName name
	 * @param mixed $properties = (optional) properties object or array
	 */
	public function __construct($properties=null)
	{
		parent::__construct($properties);
	}

	/**
	 * __destruct
	 *
	 * Destroy the class
	 */
	public function __destruct()
	{
	}

	/**
	 * load
	 *
	 * Load the configuration file
	 * @param string $config = (optional) configuration file name
	 * @return string $buffer
	 * @throws \Library\Config\Exception
	 */
	public function load($config=null)
	{
		parent::load('\Library\Stream\Factory', $config);

		$this->buffer = $this->adapter->getStream();
		return $this->buffer;
	}

}
