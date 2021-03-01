<?php
namespace Library\Serialize;

/*
 * 		Serialize\iSerialize is copyright © 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */

/**
 * Serialize\iSerialize.
 *
 * Serialize adapter interface.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Serialize.
 */
interface iSerialize
{
  	/**
  	 * connect.
  	 *
  	 * Connect the adapter
  	 * @param object $properties = (optional) Library\Properties instance
  	 * @return boolean true = successful, false = failed.
  	 */
	public function connect($properties=null);

	/**
  	 * disconnect.
  	 *
  	 * Disconnect the adapter.
  	 * @return boolean true = successful, false = unable to disconnect.
  	 */
  	public function disconnect();

	/**
	 * read
	 *
	 * Read the object from the specified source without unserializing.
	 * @param string $source = (optional) source name
	 * @return string $buffer = response buffer
	 * @throws Exception
	 */
	public function read($source=null);

	/**
	 * write
	 *
	 * Write the object to the designated source.
	 * @param string $buffer = response buffer
	 * @param string $source = (optional) source name
	 * @throws Exception
	 */
	public function write(&$buffer, $source=null);

	/**
	 * properties
	 *
	 * (Sets and) returns the Properties class property.
	 * @param object $properties = (optional) connection properties
	 * @return object $properties
	 */
	public function properties($properties=null);

	/**
	 * buffer
	 *
	 * Returns the contents of the buffer class property.
	 * @return string $buffer
	 */
	public function buffer();

  	/**
  	 * adapter
  	 *
  	 * Returns the adapter class property.
  	 * @return object $adapter = adapter.
  	 */
  	public function adapter();

	/**
	 * source
	 *
	 * (Sets and) returns the source name.
	 * @param string $source = (optional) source name, null to query
	 * @return string $source
	 */
	public function source($source=null);

}
