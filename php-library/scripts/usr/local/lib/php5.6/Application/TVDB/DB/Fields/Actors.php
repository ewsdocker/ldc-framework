<?php
namespace Application\TVDB\DB\Fields;

use Application\TVDB\DB\DbFields;
use Application\TVDB\DB\Fields\FieldDescriptor;
use Application\TVDB\DB\Exception;

use Library\Properties;
use Library\Utilities\FormatVar;

/*
 *		Application\TVDB\DB\Fields\Actors is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\DB\Fields\Actors
 *
 * TVDB Actor Field definitions
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage DB
 */
class Actors extends DbFields
{
	/*
	 * 		Field Name			Field Type		Null Allowed
	 * 
	 * 		recordNumber		int(7)				no
	 * 		id					int(7)				NO
	 * 
	 * 		SeriesId			int(7)				NO
	 * 
	 * 		Name				varchar(128)		no
	 * 		Role				varchar(128)		no
	 * 		SortOrder			int(7)				no
	 * 		Image				varchar(256)		no
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
		parent::__construct('Actors');

		/*
		 *     add($fieldName,      $fieldDefinition,        $nullAllowed,  $deaultValue);
		 */
		$this->add('recordNumber', 	'int(7) auto_increment', false, 		0);
		$this->add('id', 			'int(7)', 				 false, 		0);
		$this->add('SeriesId', 		'int(7)', 				 false, 		0);
		$this->add('Name',	 		'varchar(128)', 		 false,  		'');
		$this->add('Role', 			'varchar(128)', 		 false,  		'');
		$this->add('SortOrder', 	'tinyint(1)',			 false,  		0);
		$this->add('Image', 		'varchar(256)', 		 false, 		'');
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
