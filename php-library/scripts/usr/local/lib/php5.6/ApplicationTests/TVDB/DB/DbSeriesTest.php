<?php
namespace ApplicationTests\TVDB\DB;

use Library\CliParameters;
use Library\DBO\DBOConstants as DBOConstants;

use ApplicationTests\TVDB\LoadSeriesTest;

/*
 * 		TVDB\DB\DbSeriesTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * TVDB\DB\DbSeriesTest.
 *
 * TVDB Series Table class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage TVDB.
 */

class DbSeriesTest extends LoadSeriesTest
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

		$this->a_newTableDriver($this->a_dsnParam, $this->a_userParam, $this->a_passwordParam);

		$this->a_tableExists($this->a_table);
		if ($this->a_exists)
		{
			$this->a_dropTable($this->a_table);
		}

		$this->a_handle = null;

		$this->a_options = array('DBO_OPTION_CREATE_TABLE');
		$this->a_openTableDriver($this->a_table, $this->a_dsnParam, $this->a_userParam, $this->a_passwordParam, $this->a_options);
		$this->a_showDescriptor($this->a_handle);

		$this->a_tableDescriptor();
		$this->a_tableDescriptor = $this->a_data;

		$this->a_showDescriptor($this->a_tableDescriptor);

		$this->a_tableExists($this->a_table);
		if (! $this->a_exists)
		{
			$this->a_outputAndDie('Unable to create table');
		}

		$this->a_insertData(null, $this->a_dataLink);
		$this->a_listDataObject();
	}

}
