<?php
namespace Application\TVDB\Records;

/*
 *		Application\TVDB\Records\Control is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Records\Control
 *
 * TVDB Control Base Record information
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage Records
 */
class Control extends Base
{
	/*
	 * 	The following properties are present in the record:
	 *
	 * 		ApiKey				char(16)			No
	 * 		AccountNumber		char(16)			Yes
	 * 		AccountOwner		varchar(32)			Yes
	 * 		AccountPassword		varchar(12)			Yes
	 * 		UserLanguage		varchar(2)			Yes
	 * 		TVDBLastUpdateTime	int(10)				yes
	 * 		LastUpdated			int(10)				yes
	 * 		LastRun				varchar(32)			yes
	 */

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
	 * __toString
	 *
	 * Return printable string
	 * @return string $buffer
	 */
	public function __toString()
	{
		$buffer = '';
		$this->createBuffer($buffer);
		$buffer .= "\n";

		return $buffer;
	}

}
