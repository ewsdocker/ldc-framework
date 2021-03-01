<?php
namespace Tests\Subversion;

use Library\CliParameters;

/*
 *		Subversion\SubversionTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source, 
 *	  	or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Library\Subversion\Subversion tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Subversion
 */
class SubversionTest extends \Application\Launcher\Testing\UtilityMethods
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

		$repository = CliParameters::parameterValue('repository', 'http://10.10.10.2:8000/svn/GeoCalc/trunk/');
		$cat = CliParameters::parameterValue('cat', 'GeoCalc.php');

		$this->a_newSubversion($repository);
		$this->a_ls();
		$this->a_cat($cat);
		$this->a_checkOut('TempRepository');
	}

	/**
	 * a_checkOut
	 * 
	 * Check out the repository
	 * @param string $relativePath = path to the repository output relative to current namespace
	 */
	public function a_checkOut($relativePath)
	{
		$this->labelBlock('Svn checkout Test.', 40, '*');

		$this->a_path = sprintf("%s/%s", __DIR__, $relativePath);
		$this->a_showData($this->a_path, 'Path');

		$assertion = '(($this->a_subversion->checkout($this->a_path)) !== true);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
	}

	/**
	 * a_cat
	 * 
	 * Get file listing
	 */
	public function a_cat($relativePath)
	{
		$this->labelBlock('Svn cat Test.', 40, '*');

		$this->a_relativePath = $relativePath;
		$this->a_showData($this->a_relativePath, 'RelativePath');

		$assertion = '$this->a_cat = $this->a_subversion->cat($this->a_relativePath);';
		if (! $this->assertExceptionTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		$this->a_showData($this->a_cat, 'cat');
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
