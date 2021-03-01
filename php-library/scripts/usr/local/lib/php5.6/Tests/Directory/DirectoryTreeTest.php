<?php
namespace Tests\Directory;

use Library\Directory\Exception;
use Library\Directory\DirectoryTree;
use Library\CliParameters;

/*
 *		DirectoryTreeTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * DirectoryTreeTest
 *
 * Directory\Tree tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Directory
 */
class DirectoryTreeTest extends \Application\Launcher\Testing\UtilityMethods
{
	/**
	 * __construct
	 *
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assertSetup(1, 0, 0, array('Application\Launcher\Testing\Base', 'assertCallback'));
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
	 * assertionTests
	 *
	 * run the current assertion test steps
	 * @parm string $logger = (optional) name of the logger to use, null for none 
	 */
	public function assertionTests($logger=null, $format=null)
	{
		parent::assertionTests($logger, $format);

		$name = CliParameters::parameterValue('directory', '/etc');

		$this->a_directory = null;
		$this->a_newDirectoryTree($name, 'Library\Directory\DirectoryTree');
		$this->a_treeStructure();
		$this->a_getRoot();

		$this->a_iterate('Select');
		$this->a_iterate('DB');
		
		$this->a_arrayDirectoryNode('Config');
		$this->a_getDirectory($this->a_directoryNode);
	}

	
	/**
	 * a_iterate
	 * 
	 * Iterate through entries in the directory
	 */
	public function a_iterate($file)
	{
		$this->labelBlock('Iterate', 50, '*');

		$this->a_showData($file, 'file');

		$this->a_getDirectoryNode($file);
		$this->a_getDirectory($this->a_directoryNode);
		
		$this->a_rewind();

		while($this->a_valid())
		{
			$this->a_key();
			$this->a_current();
			
			$this->a_next();
		}
	}

	/**
	 * a_next
	 * 
	 * Get next directory item
	 */
	public function a_next()
	{
		$this->labelBlock('next', 40, '*');

		$assertion = '($this->a_directory->next() !== -1);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_current
	 * 
	 * Get current directory data
	 */
	public function a_current()
	{
		$this->labelBlock('current', 40, '*');

		$assertion = '$this->a_data = $this->a_directory->current();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'a_data');
		$this->a_showData((string)$this->a_data);
	}

	/**
	 * a_key
	 * 
	 * Get current directory key
	 */
	public function a_key()
	{
		$this->labelBlock('key', 40, '*');

		$assertion = '$this->a_key = $this->a_directory->key();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_key, 'a_key');
	}

	/**
	 * a_valid
	 * 
	 * Rewind the iterator pointer.
	 * @return boolen $valid = true if valid, false if not
	 */
	public function a_valid()
	{
		$this->labelBlock('valid', 40, '*');

		$assertion = '$this->a_isValid = $this->a_directory->valid();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			;
		}
		
		$this->a_showData($this->a_isValid, 'IsValid');
		return $this->a_isValid;
	}

	/**
	 * a_rewind
	 * 
	 * Rewind the iterator pointer.
	 */
	public function a_rewind()
	{
		$this->labelBlock('rewind', 40, '*');

		$assertion = '($this->a_directory->rewind() !== true);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_arrayDirectoryNode
	 * 
	 * Get directory node in the current root
	 */
	public function a_arrayDirectoryNode($key)
	{
		$this->labelBlock('arrayDirectoryNode', 40, '*');

		$this->a_key = $key;
		$this->a_showData($this->a_key, 'key');

		$assertion = '$this->a_directoryNode = $this->a_directoryRoot[$this->a_key]->parentNode;';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_directoryNode, 'a_directoryNode');
		$this->a_showData((string)$this->a_directoryNode);
	}

	/**
	 * a_getDirectory
	 * 
	 * Get directory referenced in the node
	 * @param LLRBNode $node = node to get directory from
	 */
	public function a_getDirectory($node)
	{
		$this->labelBlock('getDirectory', 40, '*');

		$this->a_node = $node;
		$this->a_showData($this->a_node, 'node');

		$assertion = '$this->a_directory = $this->a_node->data();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_directory, 'a_directory');
		$this->a_showData((string)$this->a_directory);
	}

	/**
	 * a_getDirectoryNode
	 * 
	 * Get directory node in the current root
	 */
	public function a_getDirectoryNode($key)
	{
		$this->labelBlock('getDirectory', 40, '*');

		$this->a_key = $key;
		$this->a_showData($this->a_key, 'key');

		$assertion = '$this->a_directoryNode = $this->a_directoryRoot->search($this->a_key);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_directoryNode, 'a_directoryNode');
		$this->a_showData((string)$this->a_directoryNode);
	}

	/**
	 * a_getRoot
	 * 
	 * Get root of the directory tree
	 */
	public function a_getRoot()
	{
		$this->labelBlock('getRoot', 40, '*');

		$assertion = '$this->a_directoryRoot = $this->a_tree->directoryFolders->directoryTree;';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_directoryRoot, 'a_directoryRoot');
		$this->a_showData((string)$this->a_directoryRoot);
	}

	/**
	 * a_treeStructure
	 * 
	 * 
	 */
	public function a_treeStructure()
	{
		$this->labelBlock('TreeStructure', 40, '*');
	
		$assertion = '$this->a_buffer = $this->a_tree->treeStructure();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->assertLogMessage(sprintf("\n%s\n", $this->a_buffer));
	}

	/**
	 * a_newDirectoryTree
	 *
	 * Create a new Directory\Tree object
	 * @param string $name = directory name to set
	 * @param string $expected = name of the directory tree class
	 */
	public function a_newDirectoryTree($name, $expected)
	{
		$this->labelBlock('NEW Directory\Tree object.', 60, '*');

		if ($this->a_directory)
		{
			unset($this->a_directory);
		}

		$this->a_directoryName = $name;
		$this->a_expected = $expected;

		$this->a_showData($this->a_directoryName, 'directoryName');
		$this->a_showData($this->a_expected, 'expected');

		$assertion = '$this->a_tree = new \Library\Directory\DirectoryTree($this->a_directoryName);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_tree, 'a_tree');
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_tree, 'a_tree');
		$this->a_compareExpectedType(true, $this->a_expected, get_class($this->a_tree));
	}

}
