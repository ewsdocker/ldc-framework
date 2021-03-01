<?php
namespace Library\DOM;
use Library\Error;

/*
 * 		DOM\iDOMAdapter is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * DOM\iDOMAdapter.
 *
 * A DOM iDOMAdapter interface.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage DOM.
 */
interface iDOMAdapter
{
  	/**
  	 * connect.
  	 *
  	 * connect the caller.
	 * @param mixed $properties = (optional) Library\Properties class instance or array
  	 * @throws Library\DOM\Exception.
  	 */
	public function connect($properties=null);

	/**
  	 * disconnect.
  	 *
  	 * disconnect from the user
  	 */
  	public function disconnect();

	/**
	 * load
	 *
	 * Load from source entry point
	 * @return string $buffer = response buffer
	 * @throws DOM\Exception
	 */
	public function load();

	/**
	 * save
	 *
	 * Save to the source entry point
	 * @param string $buffer = response buffer
	 * @throws DOM\Exception
	 */
	public function save($buffer);

	/**
	 * properties
	 *
	 * get/set the connection properties
	 * @param array $properties = (optional) connection properties
	 * @return array $properties
	 */
	public function properties($properties=null);

	/**
	 * buffer
	 *
	 * get cache buffer
	 * @return string $buffer
	 */
	public function buffer();

  	/**
  	 * adapter
  	 *
  	 * get the instance adapter.
  	 * @return object $adapter = adapter.
  	 */
  	public function adapter();

	/**
	 * adapterName
	 *
	 * Returns the name of the DOM adapter.
	 * @return string $adapterName
	 */
	public function adapterName();

}
