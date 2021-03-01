<?php
namespace Tests\DBO\Table\Column;

use Library\CliParameters;
use Library\DBO\DBOConstants;

/*
 * 		DBO\Table\Column\DescriptorTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * DBO\Table\Column\DescriptorTest.
 *
 * DBO Table Column Descriptor class tests.
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

		$columns = array();

		$this->a_columnDescriptor(0, array('name' 		=> 'id',
										   'type' 		=> 'int(4)',
										   'null' 		=> 'NO',
										   'attributes'	=> 'AUTO_INCREMENT',
										   ));

		array_push($columns, $this->a_descriptor);

		$this->a_columnDescriptor(1, array('name' 	=> 'lastname',
										   'type' 	=> 'varchar(36)',
										   'null' 	=> 'NO',
										   ));

		array_push($columns, $this->a_descriptor);

		$this->a_columnDescriptor(2, array('name' 	=> 'firstname',
										   'type' 	=> 'varchar(24)',
										   'null' 	=> 'NO',
										   ));

		array_push($columns, $this->a_descriptor);

		$this->a_columnDescriptor(3, array('name' => 'middlename',
										   'type' => 'varchar(24)',
										   'null' => 'NO',
										   ));

		array_push($columns, $this->a_descriptor);

		$this->a_columnDescriptor(4, array('name' => 'sex',
										   'type' => 'char(1)',
										   'null' => 'NO',
										   ));

		array_push($columns, $this->a_descriptor);

		$this->a_columnDescriptor(5, array('name' => 'birthdate',
										   'type' => 'char(10)',
										   'null' => 'NO',
										   ));

		array_push($columns, $this->a_descriptor);

		$this->a_showData($columns, 'COLUMNS ARRAY');
	}

	/**
	 * a_columnDescriptor
	 *
	 * Create a new DBO\Table\Column Descriptor class instance
	 * @param mixed $column = column name
	 * @param array $records = (optional) array of column values
	 */
	public function a_columnDescriptor($column, $records=array())
	{
		$this->labelBlock('Column Descriptor', 40, '*');

		$this->a_column = $column;
		$this->a_records = $records;

		$this->a_showData($this->a_column,	'a_column');
		$this->a_showData($this->a_records,	'a_records');

		$assertion = '$this->a_descriptor = new \Library\DBO\Table\Column\Descriptor($this->a_column, $this->a_records);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_descriptor, 'a_descriptor');
		$this->a_showData((string)$this->a_descriptor);
	}

}
