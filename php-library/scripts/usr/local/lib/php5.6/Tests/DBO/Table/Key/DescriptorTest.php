<?php
namespace Tests\DBO\Table\Key;

use Library\CliParameters;
use Library\DBO\DBOConstants;

/*
 * 		DBO\Table\DescriptorTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * DBO\Table\DescriptorTest.
 *
 * DBO Table Descriptor class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage DBO.
 */

class DescriptorTest extends \Application\Launcher\Testing\UtilityMethods
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

		$this->a_keyDescriptor('primary', 'id', array('id' => null));
		$this->a_keyDescriptor('unique', 'fullname', array('lastname' => null, 'firstname' => null, 'middlename' => null));
	}

	/**
	 * a_keyDescriptor
	 *
	 * Create a new DBO\Table\Key Descriptor class instance
	 * @param string $indexType = (optional) type of indes
	 * @param string $indexName = (optional) index name - if null, created from indexFields 
	 * @param string $indexFields = (optional) array of field names and lengths making up the index (key)
	 */
	public function a_keyDescriptor($indexType='index', $indexName='', $indexFields=array())
	{
		$this->labelBlock('Key Descriptor', 40, '*');

		$this->a_indexType = $indexType;
		$this->a_indexName = $indexName;
		$this->a_indexFields = $indexFields;
		
   		$this->a_showData($this->a_indexType,	'a_indexType');
   		$this->a_showData($this->a_indexName,	'a_indexName');
   		$this->a_showData($this->a_indexFields,	'a_indexFields');

		$assertion = '$this->a_descriptor = new \Library\DBO\Table\Key\Descriptor($this->a_indexType, $this->a_indexName, $this->a_indexFields);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
		
		$this->a_showData($this->a_descriptor, 'a_descriptor');
		$this->a_showData((string)$this->a_descriptor);
	}

}
