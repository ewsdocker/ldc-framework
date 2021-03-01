<?php
namespace Application\TVDB\DB\Fields;

use Library\DBO\DBOConstants as DBOConstants;
use Library\DBO\Table\Column\Descriptor as ColumnDescriptor;

use Library\Error;
use Library\Properties;
use Library\Utilities\FormatVar;

use Library\MySql\Table;
use Library\MySql\Table\Descriptor;

/*
 *		Application\TVDB\DB\Fields\FieldDescriptor is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\DB\Fields\FieldDescriptor
 *
 * TVDB table field descriptor
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage DB
 */
class FieldDescriptor extends ColumnDescriptor
{
	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string 	$name   = column name
	 * @param string 	$type 	 = column type definition string
	 * @param bool   	$null    = true if null allowed, false if not
	 * @param mixed  	$default = default field value (initial value)
	 * @param integer	$column	 = assigned column
	 */
	public function __construct($name, $type, $null, $default, $column)
	{
		parent::__construct($column, array('name' 		 => $name,
								  		   'type' 	 	 => $type,
								  		   'null' 		 => $null,
								  		   'default' 	 => $default,
								  		   ));
	}

	/**
	 * __destruct
	 * 
	 * Class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

}
