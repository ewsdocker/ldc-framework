<?php
namespace ApplicationTests\TVDB;

use Library\CliParameters;
use Application\Utility\Support;

/*
 *		TVDB\LoadActorsTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source, 
 *	  		or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Application\TVDB\LoadActorsTest tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage TVDB
 */
class LoadActorsTest extends TVDBTestLib
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
		$this->a_table = 'Actors';
		$this->a_xmlFile = 'actors.xml';

		parent::assertionTests($logger, $format);

		$this->a_newDataClass($this->a_table, $this->a_seriesUrl);

		$this->a_records();
		$this->a_listRecordData();
	}

}
