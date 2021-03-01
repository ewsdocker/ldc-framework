<?php
namespace Library\Subversion;

use Library\Error;
use Library\Exception\Descriptor;
use Library\Properties;
use Library\Utilities\FormatVar;

/*
 *		Library\Subversion\FileInfo is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Subversion\FileInfo
 *
 * Subversion repository file information
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Subversion
 */
class FileInfo extends Properties
{
	/*
	 * 
	 * 		name		directory name/file name
	 * 
	 * 		directory	directory name
	 * 		file		file name
	 * 
	 * 		type		'file' or 'dir'
	 * 		size		byte file size
	 * 
	 * 		time		time of last edit (M d H:i)
	 * 		time_t		unix time
	 * 
	 * 		created_rev	revision number of last edit
	 * 		last_author	author name (string) of last edit
	 * 
	 */

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $repoName = name of the repo file
	 * @param mixed $properties = array or properties object containing the file info
	 */
	public function __construct($repoName, $properties)
	{
		parent::__construct($properties);

		$this->file = explode("/", $this->name);
		$this->name = $repoName;
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
		$buffer  = sprintf("Name: %s\n", $this->name);

		$buffer .= sprintf("      File:        %s\n", FormatVar::format($this->file), null);
		$buffer .= sprintf("      Type:        %s\n", $this->type);
		$buffer .= sprintf("      Size:        %s\n", $this->size);
		$buffer .= sprintf("      Time:        %s\n", $this->time);
		$buffer .= sprintf("      Time_t:      %s\n", $this->time_t);
		$buffer .= sprintf("      Created_rev: %s\n", $this->created_rev);
		$buffer .= sprintf("      Last_author: %s\n", $this->last_author);
		
		return $buffer;
	}

}
