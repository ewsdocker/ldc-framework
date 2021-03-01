<?php
namespace Library\DBO\Table;

use Library\DBO\Exception;
use Library\Error;

/*
 * 		DBO\Table\Create is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Create
 *
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DBO
 */
class Create
{
	/**
	 * adapterName
	 * 
	 * Name of the table adapter
	 * @var string $adapterName
	 */
	protected 			$adapterName;

	/**
	 * adapter
	 * 
	 * Instance of the table adapter
	 * @var object $adapter
	 */
	protected			$adapter;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $tableData = (optional) table data array
	 * @param array $columnData = (optional) column data array
	 * @param array $keyData = (optional) key data array
	 * @throws \Library\DBO\Exception, Library\MySql\Exception, mysqli_exception
	 */
	public function __construct($adapterName='', $tableData=null, $columnData=null, $keyData=null)
	{
		$this->adapterName = $adapterName;

		$arguments = array($this->adapterName, $tableData, $columnData, $keyData);
		$this->adapter = call_user_func_array(array('Library\DB\Table\Factory', 'instantiateClass'), $arguments);
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
		$this->adapter = null;
	}

	/**
	 * __get
	 *
	 * Returns the requested value or null if not found
	 * @param string $name = name of the property to return
	 * @return mixed|null $value = value of the property, or null if not valid
	 * @throws \Library\DBO\Exception, Library\MySql\Exception, mysqli_exception
	 */
	public function __get($name)
	{
		if (property_exists($this, $name))
		{
			return $this->$name;
		}

		return $this->adapter->$name;
	}

	/**
	 * __set
	 *
	 * Stores the supplied value in the property $name, if it exists
	 * @param string $name = name of the property
	 * @param mixed $value = value of the property to set
	 * @throws \Library\DBO\Exception, Library\MySql\Exception, mysqli_exception
	 */
	public function __set($name, $value)
	{
		if (property_exists($this, $name))
		{
			$this->$name = $value;
		}
		else
		{
			$this->adapter->$name = $value;
		}
	}
	
	/**
	 * __call
	 *
	 * Trap the failed method call and try to re-direct it to the proper object
	 * @param string $method = name of the method being called
	 * @param array $arguments = array of class arguments
	 * @return mixed $result
	 * @throws \Library\DBO\Exception, Library\MySql\Exception, mysqli_exception
	 */
	public function __call($method, $arguments)
	{
		$this->connected();

		if (method_exists($this->adapter, $method))
		{
			return call_user_func_array(array($this->adapter, $method), $arguments);
		}

		throw new Exception(Error::code('UnknownClassMethod'));
	}

}
