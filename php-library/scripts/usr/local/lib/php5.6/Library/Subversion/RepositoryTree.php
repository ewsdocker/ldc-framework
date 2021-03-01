<?php
namespace Library\Subversion;

use Library\LLRBTree\MultiBranch\Tree as MBTree;
use Library\Error;

/*
 * 		Subversion\RepositoryTree is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Subversion\RepositoryTree
 *
 * Tree based repository directory listing
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Subversion
 */
class RepositoryTree extends MBTree
{
	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $path = absolute path to the directory
	 * @throws LLRBTree\Exception, LLRBTree\MultiBranch\Exception
	 */
	public function __construct($path)
	{
		parent::__construct($path);
	}

	/**
	 * __destruct
	 * 
	 * Class destructor
	 */
	public function __destruct()
	{
	}

	/**
	 * __toString
	 * 
	 * Return a string representation (for debugging purposes)
	 * @return string $buffer
	 */
	public function __toString()
	{
		return parent::__toString();
	}

}
