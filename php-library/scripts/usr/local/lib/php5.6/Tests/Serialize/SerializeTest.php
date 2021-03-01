<?php
namespace Tests\Serialize;
use Library\Serialize;

/*
 *		SerializeTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * SerializeTest
 *
 * SerializeBuffer tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage Serialize
 */
class SerializeTest extends \Application\Launcher\Testing\UtilityMethods
{
	/**
	 * __construct
	 *
	 * Class constructor
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

    	$this->a_serialize = null;

    	$this->a_absoluteFileName('Tests\Serialize\data.txt');
    	$this->a_properties = new \Library\Properties(array('Serialize_Source' => $this->a_fileName));

    	$this->a_name = \Library\CliParameters::parameterValue('adapter', 'buffer');
    	$this->a_class = \Library\Serialize\Factory::getInstance()->className($this->a_name);

    	$this->a_newSerialize('Library\Serialize');

    	$this->a_buildTable();

    	$this->a_printArray($this->a_table, 'Table', false, false, true);

    	$this->a_serializeTable();
    	$this->a_unSerializeTable($this->a_table);
    }

    /**
     * a_unSerializeTable
     *
     * Serialize the table in $this->a_table
     */
    public function a_unSerializeTable()
    {
    	$this->labelBlock('Unserialize Table.', 40, '*');

    	$this->a_localArray = array();
    	$assertion = sprintf('$this->a_serialize->load($this->a_localArray, "%s");', $this->a_fileName);
		if (! $this->assertExceptionFalse($assertion, sprintf('Unserialize Table - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_printArray($this->a_localArray, 'Local Array', false, false, true);

		$this->a_compareArray($this->a_table, true, $this->a_localArray);
    }

    /**
     * a_serializeTable
     *
     * Serialize the table in $this->a_table
     */
    public function a_serializeTable()
    {
    	$this->labelBlock('Serialize Table.', 40, '*');

		$assertion = sprintf('$this->a_serialize->save($this->a_table, "%s");', $this->a_fileName);
		if (! $this->assertExceptionFalse($assertion, sprintf('Serialize Table - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_buildTable
     *
     * Build a multi-level array
     */
    public function a_buildTable()
    {
    	$this->a_table =
		         array('table' => array('name' 				=> 'Employee',
			                 	  		'ifnotexists' 		=> true,
			 			     	  		'engine' 			=> 'myisam',
						   		  		'collation'			=> 'utf8_unicode_ci',
						   		  		'collateDefault'   	=> false,
						   		  		'charset'			=> 'utf8',
						   		  		'charsetDefault'	=> true,
						   		  		'autoincrement'		=> '0',
						   		  		'comment'			=> 'Employee basic data',
						   		  		),

		              'columns' => array(  0 => array('field' 		=> 'id',
			  									      'type'  		=> 'int(12)',
									     			  'null'  		=> 'NO',
									     			  'extra' 		=> 'auto_increment'),

						      			   1 => array('field' 		=> 'firstname',
								  	     			  'type'  		=> 'varchar(36)',
									     			  'null'  		=> 'NO'),

						     			   2 => array('field' 		=> 'middlename',
									  	 			  'type'  		=> 'varchar(36)',
									     			  'null'  		=> 'NO'),

						      			   3 => array('field' 		=> 'lastname',
									     			  'type'  		=> 'varchar(36)',
									     			  'null'  		=> 'NO'),

						      			   4 => array('field' 		=> 'streetaddress',
									     			  'type'  		=> 'varchar(36)',
									     			  'null'  		=> 'NO'),

						      			   5 => array('field' 		=> 'city',
									     			  'type'  		=> 'varchar(48)',
									     			  'null'  		=> 'NO'),

						     			   6 => array('field' 		=> 'state',
									     			  'type'  		=> 'char(2)',
									     			  'null'  		=> 'NO'),

						     			   7 => array('field' 		=> 'zip',
									     			  'type'  		=> 'varchar(9)',
									     			  'null'  		=> 'NO'),

						     			   8 => array('field'		=> 'valid',
						   			     			  'type'			=> 'tinyint(1)',
						   			     			  'null'			=> 'NO',
						   			     			  'default'		=> '1'),

						      			   9 => array('field'		=> 'active',
						   		 	     			  'type'			=> 'tinyint(1)',
						   			     			  'null'			=> 'NO',
						   			     			  'default'		=> '1'),
						      			  ),

						"keys" => array(   0 => array('indexes' => array('index' 		=> 'primary',
									                     				 'type'  		=> 'primary',
									  				     				 'fields'  	    => array('id' => 0)),
                      			      				  'sizes'   => null),
						  				   1 => array('indexes' => array('index'        => 'person',
						                                 				 'type'         => 'unique',
						                                 				 'fields'		=> array('lastname'   => 3,
						    													 				 'middlename' => 2,
						   														 				 'firstname'  => 1)),
						   			  				  'sizes'	=> null)),
		                );

    	    }

    /**
     * a_newSerialize
     *
     * Create a new Serialize object
     */
    public function a_newSerialize($expected)
    {
    	$this->labelBlock('Create new Serialize object.', 40, '*');

    	if ($this->a_serialize)
    	{
    		unset($this->a_serialize);
    	}

    	$assertion = sprintf('$this->a_serialize = new \Library\Serialize("%s", $this->a_properties);', $this->a_name);
		if (! $this->assertExceptionTrue($assertion, sprintf('Creating new Serialize - Asserting: %s', $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, $expected, get_class($this->a_serialize));

		$this->a_localSerialize = array();
		$this->a_serializeLevels = 0;
    }

}
