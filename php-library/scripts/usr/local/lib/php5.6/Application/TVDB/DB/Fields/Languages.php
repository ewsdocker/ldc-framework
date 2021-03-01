<?php
namespace Application\TVDB\DB\Fields;

use Application\TVDB\DB\DbFields;
use Application\TVDB\DB\Fields\FieldDescriptor;
use Application\TVDB\DB\Exception;

use Library\Properties;
use Library\Utilities\FormatVar;

/*
 *		Application\TVDB\DB\Fields\Languages is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\DB\Fields\Languages
 *
 * TVDB Language Field definitions
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage DB
 */
class Languages extends DbFields
{
	/*
	 * 		Field Name			Field Type		Null Allowed
	 * 
	 * 		recordNumber		int(7)				no
	 * 
	 * 		id					int(7)				NO
	 * 		name				varchar(64)			NOT NULL
	 * 		abbreviation		char(2)				NOT NULL
	 * 
	 */

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @throws \Library\MySql\Exception, \Library\DBO\Exception
	 */
	public function __construct()
	{
		parent::__construct('Languages');

		/*
		 *     add($fieldName,      $fieldDefinition,        $nullAllowed,  $deaultValue);
		 */
		$this->add('recordNumber',	'int(7) auto_increment', false, 		0);
		$this->add('id',	 		'int(7)', 				 false, 		0);
		$this->add('name',	 		'varchar(64)',			 false, 		'English');
		$this->add('abbreviation',	'char(2)', 				 false,  		'en');
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
