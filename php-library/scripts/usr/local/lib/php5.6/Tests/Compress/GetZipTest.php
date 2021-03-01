<?php
namespace Tests\Compress;

use Library\Compress;
use Library\Error;

/*
 * 		Compress\GetZipTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * Compress\GetZipTest.
 *
 * GetZip class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage Compress.
 */

class GetZipTest extends \Application\Launcher\Testing\UtilityMethods
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
     */
    public function assertionTests($logger=null, $format=null)
    {
 		parent::assertionTests($logger, $format);

 		$fileSource = \Library\CliParameters::parameterValue('source', null);
 		$fileDest = \Library\CliParameters::parameterValue('dest', null);
 		$extractUrl = \Library\CliParameters::parameterValue('extract', null);

 		$this->a_absoluteFileName($fileDest);

 		$this->a_newGetZip($fileSource, $this->a_fileName, $extractUrl);
    }

    /**
     * a_newGetZip
     *
     * Get a new GetZip object and download the source file to destination file
     * @param string $source = source file url
     * @param string $destination = destination file url
     * @param string $extract = (optional) extract url, null to not extract
     */
    public function a_newGetZip($source, $destination, $extract=null)
    {
		$this->labelBlock('new GetZip.', 40, '*');

		$this->a_source = $source;
		$this->a_destination = $destination;
		$this->a_extract = $extract;

		$this->a_showData($this->a_source, 'Source Url');
		$this->a_showData($this->a_destination, 'Destination Url');
		$this->a_showData($this->a_extract, 'Extract Url');

$this->a_getZip = new \Library\Compress\GetZip($this->a_source, $this->a_destination, $this->a_extract);
/*
		$assertion = '$this->a_getZip = new \Library\Compress\GetZip($this->a_source, $this->a_destination, $this->a_extract);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			;
		}
*/
		$this->a_compareExpectedType(true, get_class($this->a_getZip), 'Library\Compress\GetZip');

		$this->a_showData($this->a_getZip, 'GetZip');
    }

}
