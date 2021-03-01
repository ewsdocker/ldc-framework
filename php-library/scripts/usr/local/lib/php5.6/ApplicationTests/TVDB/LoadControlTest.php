<?php
namespace ApplicationTests\TVDB;

/*
 *		TVDB\LoadControlTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *	  		or from http://opensource.org/licenses/academic.php
*/
/**
 *
 * Application\TVDB\LoadControlTest tests
 *
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage TVDB
 */
class LoadControlTest extends TVDBTestLib
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
		$this->a_table = 'Control';
		$this->a_xmlFile = '';

		parent::assertionTests($logger, $format);

		$localArray = array('ApiUrl'				=> $this->a_apiUrl,
							'ApiKey' 				=> $this->a_apiKey,
						    'AccountNumber' 		=> $this->a_account,
						    'AccountOwner' 			=> $this->a_accountOwn,
							'AccountPassword' 		=> $this->a_accountPwd,
							'UserLanguage'			=> 'en',
							'TVDBLastUpdateTime'	=> 0,
							'LastUpdated'			=> 0,
							);

		$this->a_newDataClass($this->a_table, $localArray);

		$this->a_records();
		$this->a_listRecordData();
	}

	/**
	 * a_newDataClass
	 *
	 * Create a new data class object
	 * @param string $class = TVDB data class name to create
	 * @param array $properties = array of control properties settings
	 */
	public function a_newDataClass($class, $properties=null, $dummy=null)
	{
		$this->labelBlock('New Data Class Test.', 60, '*');

		$this->a_class = $class;
		$this->a_properties = $properties;

		$this->a_showData($this->a_class, 'Data Class');
		$this->a_showData($this->a_properties, 'Properties');

		$assertion = sprintf('$this->a_dataLink = new \Application\TVDB\%s($this->a_properties);', $this->a_class);
		if (! $this->assertTrue($assertion, sprintf('Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, sprintf('Application\TVDB\%s', $this->a_class), get_class($this->a_dataLink));

		$this->a_showData($this->a_dataLink, 'Data Link');
		$this->a_showData((string)$this->a_dataLink);
	}

}
