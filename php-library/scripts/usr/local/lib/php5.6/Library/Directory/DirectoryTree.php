<?php
namespace Library\Directory;

use Library\Directory;
use Library\Error;
use Library\LLRBTree\MultiBranch\Tree as MultiBranchTree;

/*
 *		Directory\DirectoryTree is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *		Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * Directory\DirectoryTree
 *
 * Directory content Tree class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Directory
 */
class DirectoryTree extends MultiBranchTree
{
	/**
	 * directoryFolders
	 * 
	 * Root node of the directory tree, or null if empty
	 * @var object $directoryFolders
	 */
	public $directoryFolders;

	/**
	 * directoryName
	 * 
	 * The path to the directory
	 * @var string $directoryName
	 */
	public $directoryName;

	/**
	 * directories
	 * 
	 * Array containing $directoryName parsed into an array of directories
	 * @var array $directories
	 */
	public $directories;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $directoryName = absolute path to the directory
	 * @param integer $order = (optional) sort order constant (default = SCANDIR_SORT_ASCENDING)
	 * @param resource $context = (optional) stream context (default = null)
	 * @throws Directory\Exception, LLRBTree\Exception, Exception
	 */
	public function __construct($directoryName, $order=SCANDIR_SORT_ASCENDING, $context=null)
	{
		if (($this->directoryName = $directoryName) === '')
		{
			$this->directoryName = "/";
		}

		Directory::change($this->directoryName);

		$this->directories = explode("/", $this->directoryName);

		$this->directoryFolders = new DirectoryFolder($this->directoryName, $order, $context);
		if ($this->directoryFolders->directoryTree == null)
		{
			throw new Exception(Error::code('DirectoryTreeError'));
		}
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
		$buffer = get_class($this);
		$buffer .= ":\n";
		$buffer .= (string)$this->directoryFolders;

		return $buffer;
	}

	public function treeStructure()
	{
		return sprintf("%s\n", $this->directoryFolders->folderStructure(0));
	}

}
