<?php
namespace Tests\MySql;

use Library\CliParameters;
use Library\DBO\DBOConstants;
use Library\Utilities\FormatVar;

/*
 * 		MySql\DbTest is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * MySql\DbTest.
 *
 * MySql Driver class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage MySql.
 */

class DbTest extends \Application\Launcher\Testing\UtilityMethods
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

		$host = CliParameters::parameterValue('host', '10.10.10.4');
		$db = CliParameters::parameterValue('db', 'ProjectLibraryTest');
		$table = 'Users';

		$dsn = sprintf('mysql:host=%s;port=3306;charset=UTF8;dbname=%s', $host, $db);

		$user = CliParameters::parameterValue('user', 'phplibuser');
		$password = CliParameters::parameterValue('password', 'phplibpwd');

		$this->a_newDb($dsn, $user, $password);
		
		$this->a_getDbAttribute(DBOConstants::ATTR_SERVER_INFO, 'Server Info');
		$this->a_getDbAttribute(DBOConstants::ATTR_DRIVER_HANDLE, 'dbLink');

		$this->a_dbLink = $this->a_data;
		$this->a_dbHandleFirst = $this->a_dbHandle;

		$this->a_newDb($this->a_dbLink);

		$this->a_getDbAttribute(DBOConstants::ATTR_SERVER_INFO, 'Server Info');
		$this->a_getDbAttribute(DBOConstants::ATTR_HOST_INFO, 'Host Info');
	}

	/**
	 * a_getDbAttribute
	 *
	 * Get a Db attributes value
	 * @param string $attribute = attribute value to get
	 */
	public function a_getDbAttribute($attribute, $label=null)
	{
		$this->labelBlock('Get DB Attribute', 60, '*');
	
		if ($label === null)
		{
			$label = 'attribute';
		}

		$this->a_attribute = $attribute;
		$this->a_label = $label;

		$this->a_showData($this->a_label, 'a_label');
		$this->a_showData($this->a_attribute, 'a_attribute');

		$assertion = '$this->a_data = $this->a_dbHandle->getAttribute($this->a_attribute);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	
		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_data, $this->a_label);
	}

	/**
	 * a_newDb
	 *
	 * Open the Db object handle
	 * @param string $dsn = Data Source Name
	 * @param array $options = array of dsn options
	 */
	public function a_newDb($dsn, $user='', $password='', $attributes=array())
	{
		$this->labelBlock('New Db.', 60, '*');

		$this->a_dsn = $dsn;
		$this->a_user = $user;
		$this->a_password = $password;
		$this->a_attributes = $attributes;

   		$this->a_showData($this->a_dsn,		'a_dsn');
   		$this->a_showData($this->a_user,	   'a_user');
   		$this->a_showData($this->a_password,   'a_password');
   		$this->a_showData($this->a_attributes, 'a_attributes');

//$this->a_dbHandle = new \Library\MySql\Db($this->a_dsn, $this->a_user, $this->a_password, $this->a_attributes);

		$assertion = '$this->a_dbHandle = new \Library\MySql\Db($this->a_dsn, $this->a_user, $this->a_password, $this->a_attributes);';
		if (! $this->assertExceptionTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_showData($this->a_dbHandle, 'a_dbHandle');
	}

}
