<?php
namespace Library\Config;
use Library\Error;

/*
 * 		Config\IOAdapterFile is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Config\IOAdapterFile.
 *
 * Adapter to interface to the FileIO adapter
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Config.
 */
class IOAdapterFile extends IOAdapterBase
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param mixed $properties = (optional) properties array or object
	 */
	public function __construct($properties=null)
	{
		parent::__construct($properties);
	}

	/**
	 * __destruct
	 *
	 * Destroy the class
	 * @return null
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
		$this->connect('\Library\FileIO\Factory', $config);

		$this->buffer = '';
		foreach($this->adapter as $lineNumber => $text)
		{
			$this->buffer .= $text;
		}

		return $this->buffer;
	}

}
