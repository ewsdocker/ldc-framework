<?php
namespace Application\TVDB\Records;

/*
 *		Application\TVDB\Records\Mirror is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\Records\Mirror
 *
 * TVDB Mirror Record information
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage Records
 */
class Mirror extends Base
{
	/*
	 * 	The following properties are present in the record:
	 *
	 * 		id				TVDB mirror id
	 * 		mirrorpath		URL of the mirrors.xml file
	 * 		typemask		Mirrors capability - 3-bit mask: bit 0 (1) = xml, bit 1 (2) = banners, bit 2 (4) = zips
	 *
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
