<?php
namespace Library\DB\Table;
use Library\DB\Exception;
use Library\Error;

/*
 * 		DB\Table\TableAbstract is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * TableAbstract
 *
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DBO.
 */
abstract class TableAbstract
{
	/**
	 * tableData
	 *
	 * An array containing the table related parameters
	 * @var array $tableData
	 */
	protected $tableData;

	/**
	 * columnData
	 *
	 * An array containing the column related parameters
	 * @var array $columnData
	 */
	protected $columnData;

	/**
	 * keyData
	 *
	 * An array containing the key related parameters
	 * @var array $keyData
	 */
	protected $keyData;

	/**
	 * tableDescriptor
	 *
	 * Current table descriptor created from tableData, columnData and keyData
	 * @var \Library\Table\Descriptor $tableDescriptor
	 */
	protected $tableDescriptor;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $tableData = (optional) table data array
	 * @param array $columnData = (optional) column data array
	 * @param array $keyData = (optional) key data array
	 * @throws \Library\DB\Exception
	 *
	abstract public function __construct($tableData=null, $columnData=null, $keyData=null);

	/**
	 * __destruct
	 *
	 * Class destructor
	 *
	abstract public function __destruct();

	/**
	 * __get
	 *
	 * Returns the requested value or null if not found
	 * @param string $name = name of the property to return
	 * @return mixed|null $value = value of the property, or null if not valid
	 *
	abstract public function __get($name);

	/**
	 * __set
	 *
	 * Stores the supplied value in the property $name, if it exists
	 * @param string $name = name of the property
	 * @value mixed $value = value of the property to set
	 *
	abstract public function __set($name, $value);

	/**
	 * createTable
	 *
	 * create a database table from table, column and key data arrays
	 * @param array $tableData = (optional) table data array
	 * @param array $columnData = (optional) column data array
	 * @param array $keyData = (optional) key data array
	 * @throws \Library\DB\Exeption
	 */
	abstract public function createTable($tableData=null, $columnData=null, $keyData=null);

}
