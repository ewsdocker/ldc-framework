<?php
namespace Library\LLRBTree;

/*
 * 		Stack is copyright � 2008, 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * Stack.
 *
 * Extension to Stack class to properly handle a stack of LLRBTree nodes
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2008, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage LLRBTree.
 */
class Stack extends Library\Stack
{
	/**
	 * name
	 * 
	 * Stack name
	 * @var string $name
	 */
	private		$name;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param string $name = name of the stack
	 * @param object $stack = (optional) stack to use for initialization
	 */
	public function __construct($name, $stack=null)
	{
		parent::__construct($stack);
		$this->name = $name;
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

	/**
	 * name
	 *
	 * get/set the tree stack name
	 * @param string $name = (optional) name to set, null to query
	 * @return string $name
	 */
	public function name($name=null)
	{
		if ($name !== null)
		{
			$this->name = $name;
		}

		return $this->name;
	}

	/**
	 * __toString
	 *
	 * convert the LLRBTree stack into a printable tree layout
	 * @return string $buffer
	 */
	public function __toString()
	{
		$class = get_class();
		$classTree = 'Library\LLRBTree';

		$buffer = '';
		foreach($this as $key => $value)
		{
			$buffer .= "\n\t";
			if (is_a($value, $class) || is_a($value, $classTree))
			{
				$buffer .= sprintf("%s[%d]", $value->name(), $key);
			}
			else
			{
				$buffer .= sprintf("%s[%d]", $this->name, $key);
			}

			$buffer .= sprintf("\n\t\t%s", $value);
		}

		return $buffer;
	}

}
