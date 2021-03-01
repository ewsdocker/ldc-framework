<?php
namespace Application\TVDB\DB;

use Library\Properties;
use Library\Utilities\FormatVar;

/*
 * 		TVDB\DB\DbObject is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * TVDB\DB\DbObject.
 *
 * TVDB DB object to contain the result of a query.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage DB.
 */

class DbObject extends Properties
{
	/**
	 * __construct
	 *
	 * Class constructor
	 */
	public function __construct($tableName=null)
	{
		parent::__construct();
		$this->tableName = $tableName;
	}

	/**
	 * __destruct
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * __toString
	 *
	 * Returns a printable string of the data objects
	 * @return string $buffer = printable string containing the complete data object values
	 */
	public function __toString()
	{
		FormatVar::sort(false);
		FormatVar::sortValues(false);
		FormatVar::recurse(false);
		FormatVar::formatted(true);

		return parent::__toString();
	}

}
