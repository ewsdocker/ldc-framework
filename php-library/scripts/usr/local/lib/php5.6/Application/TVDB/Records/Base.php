<?php
namespace Application\TVDB\Records;

use Library\Properties;

/*
 *		Application\TVDB\Records\Base is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Records\Base
 *
 * TVDB Records Common methods
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage Records
 */
class Base extends Properties
{
	/**
	 * __construct
	 *
	 * Class constructor
	 * @param mixed $properties = array or properties object containing the file info
	 */
	public function __construct($properties)
	{
		parent::__construct($properties);
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
	 * createBuffer
	 *
	 * Create a printable buffer
	 * @param string $buffer = buffer to add data to (passed-by-reference)
	 * @throws Exception
	 */
	protected function createBuffer(&$buffer, $ignoreNames=array())
	{
		$buffer = '';
		if ($ignoreNames && ((! is_array($ignoreNames)) || (count($ignoreNames) == 0)))
		{
			throw new Exception(Error::code('ArrayVariableExpected'));
		}

		foreach($this as $name => $value)
		{
			if ($ignoreNames)
			{
				if (in_array($name, $ignoreNames))
				{
					continue;
				}
			}

			$this->addNotNull($buffer, $name);
		}
	}
	/**
	 * addNotNull
	 *
	 * Add the label and data to the buffer
	 * @param string $buffer = buffer (pass-by-reference)
	 * @param string $name = label
	 */
	protected function addNotNull(&$buffer, $name)
	{
		if (isset($this->{$name}))
		{
			$buffer .= sprintf("\t%s:\t\t%s\n", $name, $this->{$name});
		}

	}

}
