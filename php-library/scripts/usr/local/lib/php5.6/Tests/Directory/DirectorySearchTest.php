<?php
namespace Tests\Directory;
use Library\Directory\Exception;
use Library\CliParameters;

/*
 *		DirectorySerachTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * DirectorySearchTest
 *
 * Directory\Search tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2013 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Directory
 */
class DirectorySearchTest extends \Application\Launcher\Testing\UtilityMethods
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
		$pattern = CliParameters::parameterValue('pattern', '*.log');

		$this->a_directory = null;
		$this->a_newDirectorySearch($name, 'Library\Directory\Search');
		$this->a_searchDirectory($pattern);
		$this->a_forEach();
	}

	/**
	 * a_forEach
	 *
	 * Iterate through the search results
	 */
	public function a_forEach()
	{
		$this->labelBlock('ForEach.', 40, '*');

		foreach($this->a_directoryArray as $key => $value)
		{
			$this->assertLogMessage(sprintf('%s = %s', $key, $value));
		}
	}

	/**
	 * a_searchDirectory
	 * 
	 * Search the directory for requested pattern
	 * @param array $pattern = pattern to search for
	 */
	public function a_searchDirectory($pattern)
	{
		$this->labelBlock('Search Directory.', 40, '*');

		$this->a_pattern = $pattern;
		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_pattern, 'a_pattern');
		}

		$assertion = '(($this->a_directoryArray = $this->a_directory->select($this->a_pattern)) !== null);';
		if (! $this->assertTrue($assertion, sprintf('Getting directoryList - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_directoryArray, 'a_directoryArray');
			$this->a_outputAndDie();
		}
		
		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_directoryArray, 'a_directoryArray');
		}

	}

	/**
	 * a_newDirectorySearch
	 *
	 * Create a new Directory\Search object
	 * @param string $name = directory name to set
	 * @param string $expected = name of the Directory\Search class
	 */
	public function a_newDirectorySearch($name, $expected)
	{
		$this->labelBlock('Create new Directory\Search object.', 40, '*');

		if ($this->a_directory)
		{
			unset($this->a_directory);
		}

		$this->a_directoryName = $name;
		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_directoryName, 'a_directoryName');
			$this->a_showData($expected, 'expected');
		}

		$assertion = sprintf('$this->a_directory = new \Library\Directory\Search("%s");', $this->a_directoryName);
		if (! $this->assertTrue($assertion, sprintf('Creating new Directory\Search - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_directory, 'a_directory');
			$this->a_outputAndDie();
		}

	   	if ($this->verbose > 1)
		{
			$this->a_showData($this->a_directory, 'a_diretory');
		}

		$this->a_compareExpectedType(true, $expected, get_class($this->a_directory));
	}

}
