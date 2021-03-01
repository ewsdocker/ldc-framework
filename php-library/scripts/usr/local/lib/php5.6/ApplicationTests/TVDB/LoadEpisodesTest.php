<?php
namespace ApplicationTests\TVDB;

use Library\CliParameters;

/*
 *		TVDB\LoadEpisodesTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *	  		or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Application\TVDB\LoadEpisodesTest tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage TVDB
 */
class LoadEpisodesTest extends TVDBTestLib
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
		$this->a_table = 'Episodes';

		if (($this->a_season = CliParameters::parameterValue('season', 'all')) == 'all')
		{
			$this->a_xmlFile = 'all';
		}
		else
		{
			$this->a_episode = CliParameters::parameterValue('episode', '1');
			$this->a_xmlFile = sprintf("default/%u/%u", $this->a_season, $this->a_episode);
		}

		parent::assertionTests($logger, $format);

		$this->a_seriesUrl .= '/en.xml';

		$this->a_newDataClass($this->a_table, $this->a_seriesUrl);

		$this->a_records();
		$this->a_listRecordData();
	}

}
