<?php
namespace Tests\Subversion;

/*
 *		Subversion\FileInfoTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source, 
 *	  	or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Library\Subversion\FileInfo tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Subversion
 */
class FileInfoTest extends \Application\Launcher\Testing\UtilityMethods
{
	/**
	 * __construct
	 *
	 * Class constructor - pass parameters on to parent class
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
	 * @param string $format = (optional) format for logging
	 */
	public function assertionTests($logger=null, $format=null)
	{
		parent::assertionTests($logger, $format);

		$localArray = array('name' 			=> 'Subversion/FileInfo.php',
							'type' 			=> 'file',
							'size' 			=> 1053,
							'time' 			=> date('M d H:i'),
							'time_t' 		=> time(),
							'created_rev'	=> 12,
							'last_author'	=> 'jay',
							);

		$this->a_newFileInfo($localArray['name'], $localArray);

		$localArray['file'] = explode("/", $localArray['name']);
		$this->a_fileInfo($localArray);

		$this->a_listProperties();

		$this->a_propertyValue('name', $localArray['name']);
		$this->a_propertyValue('file', $localArray['file']);

		$this->a_printProperties();

		$this->a_exists('revision', false);
		$this->a_exists('created_rev', true);

		$this->a_getArrayProperty('last_author', $localArray['last_author']);
		$this->a_getArrayProperty('time', $localArray['time']);
	}

	/**
	 * a_getArrayProperty
	 *
	 * Get a property's value using array access
	 * @param string $property = name of the property to get
	 * @param mixed $expected = value expected
	 */
	public function a_getArrayProperty($property, $expected)
	{
		$this->labelBlock('GetArrayProperty.', 40, '*');

		$this->a_expected = $expected;
		$this->a_showData($this->a_expected, 'expected');

		$assertion = sprintf('$this->a_data = $this->a_fileInfo["%s"];', $property);
		if (! $this->assertTrue($assertion, sprintf('GetArrayProperty - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpected($this->a_expected);
	}

	/**
	 * a_exists
	 *
	 * Check if the property exists
	 * @param string $property = name of the property to check for
	 * @param boolean $type = (optional) type of comparison, default = true
	 */
	public function a_exists($property, $type=true)
	{
		$this->labelBlock('Exists.', 40, '*');

		$this->a_property = $property;

		$assertion = sprintf('$this->a_fileInfo->exists("%s");', $this->a_property);
		if ($type)
		{
			if (! $this->assertTrue($assertion, sprintf('Exists (true) - Asserting: %s', $assertion)))
			{
				$this->a_outputAndDie();
			}
		}
		else
		{
			if (! $this->assertFalse($assertion, sprintf('Exists (false) - Asserting: %s', $assertion)))
			{
				$this->a_outputAndDie();
			}
		}
	}

	/**
	 * a_printProperties
	 *
	 * Print all of the properties and their values
	 */
	public function a_printProperties()
	{
		$this->labelBlock('PrintProperties.', 40, '*');

		$assertion = '$this->a_data = (string)$this->a_fileInfo;';
		if (! $this->assertTrue($assertion, sprintf('Print Properties - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'FileInfo');
	}

	/**
	 * a_listProperties
	 *
	 * List all of the properties and their values
	 */
	public function a_listProperties()
	{
		$this->labelBlock('ListProperties.', 40, '*');

		$assertion = '$this->a_array = get_object_vars($this->a_fileInfo);';
		if (! $this->assertTrue($assertion, sprintf('List Properties - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_array, 'FileInfo');
	}

	/**
	 * a_propertyValue
	 *
	 * Get a property's value
	 * @param string $property = name of the property
	 * @param mixed $expected = expected value
	 */
	public function a_propertyValue($property, $expected)
	{
		$this->labelBlock('PropertyValue.', 40, '*');

		$this->a_property = $property;
		$this->a_expected = $expected;

		$this->a_showData($this->a_property, 'Property');
		$this->a_showData($this->a_expected, 'Expected');

		$assertion = sprintf('(($this->a_data = $this->a_fileInfo->%s) !== null);', $this->a_property);
		if (! $this->assertTrue($assertion, sprintf('PropertyValue - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareType($this->a_data, $this->a_expected);
	}

	/**
	 * a_fileInfo
	 *
	 * Get an array containing all of the properties and their values
	 * @param array $expected = array containing the expected results
	 */
	public function a_fileInfo($expected=null, $type=true)
	{
		$this->labelBlock('Get FileInfo.', 40, '*');

		$this->a_expected = $expected;
		$this->a_type = $type;

		$this->a_showData($this->a_type, 'Type');
		$this->a_showData($this->a_expected, 'Expected');

		$assertion = '(($this->a_array = $this->a_fileInfo->properties()) !== false);';
		if (! $this->assertTrue($assertion, sprintf('Get FileInfo - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareArray($this->a_array, $this->a_type, $this->a_expected);
		
		$this->a_showData($this->a_array, 'Array');
	}

	/**
	 * a_newFileInfo
	 *
	 * Create a new FileInfo class object
	 */
	public function a_newFileInfo($infoName, $infoArray)
	{
		$this->labelBlock('NEW Subversion\FileInfo Class Tests.', 60, '*');

		$this->a_repoName = $infoName;
		$this->a_localArray = $infoArray;

		$this->a_showData($this->a_repoName, 'RepoName');
		$this->a_showData($this->a_localArray, 'Info');

		$assertion = '$this->a_fileInfo = new \Library\Subversion\FileInfo($this->a_repoName, $this->a_localArray);';
		if (! $this->assertTrue($assertion, sprintf('NEW Subversion\FileInfo Class - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, 'Library\Subversion\FileInfo', get_class($this->a_fileInfo));
	}

}
