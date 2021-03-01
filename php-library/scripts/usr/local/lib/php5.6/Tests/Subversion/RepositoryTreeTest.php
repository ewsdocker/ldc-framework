<?php
namespace Tests\Subversion;

use Library\Subversion\RepositoryTree;
use Library\LLRBTree\MultiBranch\Tree as MultiBranchTree;
use Library\CliParameters;

/*
 *		RepositoryTreeTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * RepositoryTreeTest
 *
 * Subversion\RepositoryTree tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Subversion
 */
class RepositoryTreeTest extends \Application\Launcher\Testing\UtilityMethods
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

		$repository = CliParameters::parameterValue('repository', 'http://10.10.10.2:8000/svn/PHPProjectLibrary/trunk/');

		$this->a_newUrlParse($repository, 'Library\Url\Parse');
		$this->a_urlDocument();

		$this->a_newSubversion($repository);

		$this->a_directories($this->a_document);

		$this->a_directoryRoot = $this->a_directoriesArray[count($this->a_directoriesArray)-1];

		$this->a_tree = null;
		$this->a_newRepositoryTree($this->a_directoryRoot, 'Library\Subversion\RepositoryTree');

		$this->a_ls();

		foreach($this->a_directoryArray as $file => $info)
		{
			if (($file == '.') || ($file == '..'))
			{
				continue;
			}

			$this->a_newFileInfo($file, $info);
			if ($this->a_fileInfo->type == 'dir')
			{
				$type = RepositoryTree::SEARCH_BRANCH;
			}
			else 
			{
				$type = RepositoryTree::SEARCH_NAME;
			}

			$this->a_directories($file);
			$this->a_add($this->a_directoriesArray, $type, $this->a_fileInfo);

			$this->a_treeStructure();
		}

		$this->a_treeStructure();
		$this->a_getRoot();
	}

	
	/**
	 * a_add
	 * 
	 * Add repository data to the tree
	 * @param string $name = name to add
	 * @param integer $type = type of file
	 * @param mixed $info = info to add
	 */
	public function a_add($name, $type, $info)
	{
		$this->labelBlock('add', 40, '*');

		$this->a_name = $name;
		$this->a_type = $type;
		$this->a_info = $info;

		$this->a_showData($this->a_name, 'name');
		$this->a_showData($this->a_type, 'type');
		$this->a_showData($this->a_info, 'info');

		$assertion = '$this->a_node = $this->a_tree->search($this->a_name, $this->a_type, $this->a_info, true);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_node, 'a_node');
		$this->a_showData((string)$this->a_node);
	}

	/**
	 * a_ls
	 * 
	 * Get directory tree
	 */
	public function a_ls()
	{
		$this->labelBlock('Svn ls Test.', 40, '*');

		$assertion = 'is_array($this->a_directoryArray = $this->a_subversion->ls());';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_directoryArray, 'DirectoryArray');
	}

	/**
	 * a_arrayRepositoryNode
	 * 
	 * Get directory node in the current root
	 */
	public function a_arrayRepositoryNode($key)
	{
		$this->labelBlock('arrayRepositoryNode', 40, '*');

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
	 * a_getRepository
	 * 
	 * Get directory referenced in the node
	 * @param LLRBNode $node = node to get directory from
	 */
	public function a_getRepository($node)
	{
		$this->labelBlock('getRepository', 40, '*');

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
	 * a_getRepositoryNode
	 * 
	 * Get directory node in the current root
	 */
	public function a_getRepositoryNode($key)
	{
		$this->labelBlock('getRepository', 40, '*');

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

		$assertion = '$this->a_directoryRoot = $this->a_tree->branch->root;';
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
	 * a_directories
	 * 
	 * Parse $name to $directoriesArray
	 * @param string $name = name to parse
	 */
	public function a_directories($name)
	{
		$this->labelBlock('directories', 40, '*');

		$this->a_name = $name;
		$this->a_showData($this->a_name, 'name');

		$assertion = '$this->a_directoriesArray = explode("/", $this->a_name);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_directoriesArray, 'a_directoriesArray');
	}

	/**
	 * a_urlDocument
	 * 
	 * Get URL document
	 */
	public function a_urlDocument()
	{
		$this->labelBlock('URL Document', 40, '*');

		$assertion = '$this->a_document = $this->a_urlParsed->document();';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_document, 'a_document');
	}

	/**
	 * a_newFileInfo
	 * 
	 * Create new FileInfo object from FileInfo array
	 * @param string $name = object name
	 * @param array $fileInfo = array to create new FileInfo object from
	 */
	public function a_newFileInfo($name, $fileInfo)
	{
		$this->labelBlock('NEW FileInfo', 40, '*');

		$this->a_name = $name;
		$this->a_fileInfoArray = $fileInfo;

		$this->a_showData($this->a_name, 'name');
		$this->a_showData($this->a_fileInfoArray, 'fileInfo');

		$assertion = '$this->a_fileInfo = new \Library\Subversion\FileInfo($this->a_name, $this->a_fileInfoArray);';
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\Subversion\FileInfo', get_class($this->a_fileInfo));

		$this->a_showData($this->a_fileInfo, 'FileInfo');
		$this->a_showData((string)$this->a_fileInfo);
	}

	/**
	 * a_newUrlParse
	 * 
	 * Parse the repository url
	 */
	public function a_newUrlParse($url, $expected)
	{
		$this->labelBlock('NEW UrlParse', 40, '*');

		$this->a_url = $url;
		$this->a_expected = $expected;

		$this->a_showData($this->a_url, 'url');
		$this->a_showData($this->a_expected, 'expected');

		$assertion = '$this->a_urlParsed = new \Library\Url\Parse($this->a_url);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, $this->a_expected, get_class($this->a_urlParsed));
		$this->a_showData($this->a_urlParsed, 'UrlParsed');
		$this->a_showData((string)$this->a_urlParsed, 'contents');
	}

	/**
	 * a_newRepositoryTree
	 *
	 * Create a new RepositoryTree object
	 * @param string $name = directory name to set
	 * @param string $expected = name of the directory tree class
	 */
	public function a_newRepositoryTree($name, $expected)
	{
		$this->labelBlock('NEW RepositoryTree object.', 60, '*');

		if ($this->a_tree !== null)
		{
			unset($this->a_tree);
		}

		$this->a_directoryName = $name;
		$this->a_expected = $expected;

		$this->a_showData($this->a_directoryName, 'directoryName');
		$this->a_showData($this->a_expected, 'expected');

		$assertion = '$this->a_tree = new \Library\Subversion\RepositoryTree($this->a_directoryName);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_tree, 'a_tree');
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_tree, 'a_tree');
		$this->a_compareExpectedType(true, $this->a_expected, get_class($this->a_tree));
	}

	/**
	 * a_newSubversion
	 *
	 * Create a new Subversion class object
	 */
	public function a_newSubversion($repository)
	{
		$this->labelBlock('NEW Subversion Class Tests.', 60, '*');

		$this->a_repository = $repository;

		$this->a_showData($this->a_repository, 'Repository');

		$assertion = '$this->a_subversion = new \Library\Subversion($this->a_repository);';
		if (! $this->assertTrue($assertion, sprintf('NEW Subversion Class - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\Subversion', get_class($this->a_subversion));
	}

}
