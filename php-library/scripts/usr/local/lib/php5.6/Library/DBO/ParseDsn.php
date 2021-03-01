<?php
namespace Library\DBO;

use Library\Error;
use Library\Properties;

/*
 * 		DBO\ParseDsn is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DBO\ParseDsn
 *
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DBO
 */
class ParseDsn extends Properties
{
	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $dsn = Data Source Name (mysql:host=<hostname>;port=<connection port>;dbname=<database name>)
	 * @throws \Library\DBO\Exception
	 */
	public function __construct($dsn)
	{
		parent::__construct(array('dbname'	=> '', 
								  'port' 	=> 3306, 
								  'socket' 	=> '', 
								  'flags' 	=> 0,
								  'dsn'		=> $dsn,
								  ));

		list($this->driver, $dsn) = explode(':', $dsn, 2);

		$fields = explode(';', $dsn);

		foreach($fields as $index => $field)
		{
			list($name, $value) = explode('=', $field);
			parent::__set($name, $value);
		}

		if (! $this->exists('host'))
		{
			throw new Exception(Error::code('DbMissingHost'));
		}
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
	 * __toString
	 * 
	 * Return a string representation of the values
	 * @return string $buffer
	 */
	public function __toString()
	{
		return parent::__toString();
	}

	public function dsn()
	{
		return $this->dsn;
	}
}
