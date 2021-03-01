<?php
namespace Tests\Directory;
use Library\Directory\Exception;
use Library\CliParameters;

/*
 *		DirectoryContentsTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source, 
 *		   or from http://opensource.org/licenses/academic.php
*/
/**
 * DirectoryContentsTest
 *
 * Directory\Contents tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2013 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Directory
 */
class DirectoryContentsTest extends \Application\Launcher\Testing\UtilityMethods
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
		$this->a_newDirectoryContents($name, 'Library\Directory\Contents');
		$this->a_getDirectory();
		$this->a_printDirectory();

		$this->a_countDirectory();
		$this->a_forEach();
	}

	/**
	 * a_forEach
	 *
	 * Iterate through the directory
	 */
	public function a_forEach()
	{
		$this->labelBlock('ForEach.', 40, '*');

		foreach($this->a_directory as $key => $value)
		{
			$this->assertLogMessage(sprintf('%s = %s', $key, $value));
		}
	}

	/**
	 * a_countDirectory
	 *
	 * Count items in the DirectoryList
	 */
	public function a_countDirectory()
	{
		$this->labelBlock('Directory elements.', 40, '*');

		$assertion = '(($this->a_data = $this->a_directory->count()) !== null);';
		if (! $this->assertTrue($assertion, sprintf('Getting Directory\Contents size - Asserting: %s', $assertion)))
		{
			$this->a_showData($this->a_data, 'a_data');
			$this->a_outputAndDie();
		}

		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_data, 'a_data');
		}
	}

	/**
	 * a_printDirectory
	 * 
	 * Print the contents of the DirectoryContents object
	 */
	public function a_printDirectory()
	{
		$this->labelBlock('Print Directory\Contents object.', 40, '*');
		$this->assertLogMessage((string)$this->a_directory);
	}

	/**
	 * a_getDirectory
	 * 
	 * Get the directory as an array
	 */
	public function a_getDirectory()
	{
		$this->labelBlock('Get Directory\Contents.', 40, '*');

		$assertion = '(($this->a_directoryArray = $this->a_directory->directory()) !== null);';
		if (! $this->assertTrue($assertion, sprintf('Getting Directory\Contents - Asserting: %s', $assertion)))
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
	 * a_newDirectoryContents
	 *
	 * Create a new Directory\Contents object
	 * @param string $name = directory name to set
	 * @param string $expected = name of the directory list class
	 */
	public function a_newDirectoryContents($name, $expected)
	{
		$this->labelBlock('Create new Directory\Contents object.', 40, '*');

		if ($this->a_directory)
		{
			unset($this->a_directory);
		}

		$this->a_directoryName = $name;
		if ($this->verbose > 1)
		{
			$this->a_showData($this->a_directoryName, 'directoryName');
			$this->a_showData($expected, 'expected');
		}

		$assertion = sprintf('$this->a_directory = new \Library\Directory\Contents("%s");', $this->a_directoryName);
		if (! $this->assertTrue($assertion, sprintf('Creating new Directory\Contents - Asserting: %s', $assertion)))
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
