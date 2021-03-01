<?php
namespace Application\TVDB\DB\Fields;

use Application\TVDB\DB\DbFields;
use Application\TVDB\DB\Fields\FieldDescriptor;
use Application\TVDB\DB\Exception;

use Library\Properties;
use Library\Utilities\FormatVar;

/*
 *		Application\TVDB\DB\Fields\Control is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  	Refer to the file named License.txt provided with the source,
 *		 	or from http://opensource.org/licenses/academic.php
*/
/**
 * Application\TVDB\DB\Fields\Control
 *
 * TVDB Control Field definitions
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage DB
 */
class Control extends DbFields
{
	/*
	 * 		Field Name			Field Type		Null Allowed
	 *
	 * 		recordNumber		int(7)				no
	 *
	 *		ApiUrl				char(128)			No
	 * 		ApiKey				char(16)			No
	 * 		AccountNumber		char(16)			Yes
	 * 		AccountOwner		varchar(32)			Yes
	 * 		AccountPassword		varchar(12)			Yes
	 * 		UserLanguage		varchar(2)			Yes
	 * 		TVDBLastUpdateTime	int(10)				yes
	 * 		LastUpdated			int(10)				yes
	 * 		LastRun				varchar(32)			yes
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
		parent::__construct('Control');

		/*
		 *     add($fieldName,      		$fieldDefinition,        $nullAllowed,  $deaultValue);
		 */
		$this->add('recordNumber', 			'int(7) auto_increment',	 false, 		0);

		$this->add('ApiUrl',				'varchar(128)',				 false,			'http://thetvdb.com/api/');
		$this->add('ApiKey', 				'char(16)', 				 false, 		0);
		$this->add('AccountNumber',			'char(16)', 		 		 true,  		'');
		$this->add('AccountOwner',			'varchar(32)', 				 true,  		'');
		$this->add('AccountPassword', 		'varchar(12)',				 true,  		'');
		$this->add('UserLanguage',			'varchar(2)',				 true,			'');

		$this->add('TVDBLastUpdateTime',	'int(10)', 					 true, 			0);
		$this->add('LastUpdated',			'int(10)', 					 true, 			0);
		$this->add('LastRun',				'varchar(32)',				 true,			'');
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
