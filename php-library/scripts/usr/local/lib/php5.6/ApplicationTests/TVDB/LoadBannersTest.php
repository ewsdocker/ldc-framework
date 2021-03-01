<?php
namespace ApplicationTests\TVDB;

use Library\CliParameters;
use Application\Utility\Support;

/*
 *		TVDB\LoadBannersTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source, 
 *	  		or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Application\TVDB\LoadBannersTest tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage TVDB
 */
class LoadBannersTest extends TVDBTestLib
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
		$this->a_table = 'Banners';
		$this->a_xmlFile = 'banners.xml';

		parent::assertionTests($logger, $format);
		
		$this->a_newDataClass($this->a_table, $this->a_seriesUrl);

		$this->a_records();
		$this->a_listBannersArray();
		$this->a_iterateRecords();
	}

	/**
	 * a_listBannersArray
	 * 
	 * List the banner array records data
	 */
	public function a_listBannersArray()
	{
		$this->labelBlock('List Banners Array Test.', 50, '*');

		foreach($this->a_recordsArray as $element => $banner)
		{
			$this->a_showData($banner, sprintf("Banner #%u", $element));
		}
	}

	/**
	 * a_iterateRecords
	 * 
	 * List the records data
	 */
	public function a_iterateRecords()
	{
		$this->labelBlock('Iterate Record Test.', 50, '*');

		foreach($this->a_dataLink as $element => $record)
		{
			$this->a_showData($record, sprintf("Banner #%u", $element));
		}
	}

}
