<?php
namespace Application\TVDB;

use Library\Config\SectionTree as AudioNode;

/*
 *		TVDB\Node is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * TVDB\Node
 *
 * Wraps a TVDBNode object to provide direct access to the node's children
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage Node
 */
class Node
{
	/**
	 * treeNode
	 *
	 * A TVDB element node
	 * @var Node $treeNode
	 */
	private $treeNode;

	/**
	 * elementName
	 *
	 * The name of this node element
	 * @var string $elementName
	 */
	private $elementName;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $elementName = TVDB node name
	 * @param object $treeNode = the TVDB element node
	 */
	public function __construct($elementName, $treeNode)
	{
		$this->elementName = $elementName;
		$this->treeNode = $treeNode;
	}

	/**
	 * __destruct
	 *
	 * Class destructor
	 */
	public function __destruct()
	{
	}

	/**
	 * __get
	 *
	 * Magic function to get an element, if it exists, null if not
	 * @param string $element = name of the element to get
	 * @return mixed $value = value of the element, null if it does not exist
	 */
	public function __get($element)
	{
		return $this->treeNode->{$element};
	}

	/**
	 * __toString
	 *
	 * Returns a string containing the current tree data in printable format
	 * @return string $data = printable string
	 */
	public function __toString()
	{
		$data = sprintf("%s:\n\t", $this->elementName);
		$data .= (string)$this->treeNode;

		return $data;
	}

	/**
	 * treeNode
	 *
	 * Get the treeNode property
	 * @return Node $treeNode = the current $treeNode at exit
	 */
	public function treeNode()
	{
		return $this->treeNode;
	}

	/**
	 * elementName
	 *
	 * Return the name of the TVDB element
	 * @return string $elementName = elementName
	 */
	public function elementName()
	{
		return $this->elementName;
	}

}

